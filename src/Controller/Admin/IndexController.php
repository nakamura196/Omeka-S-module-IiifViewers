<?php

namespace IiifViewers\Controller\Admin;

use Interop\Container\ContainerInterface;
use IiifViewers\Form\IndexForm;
use Laminas\View\Model\ViewModel;
use Laminas\Mvc\Controller\AbstractActionController;
use Omeka\Api\Exception\ValidationException;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

/**
 * IndexController
 * アイコン設定用コントローラ
 */
class IndexController extends AbstractActionController
{
    public function __construct(ContainerInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * indexAction
     *
     *　設定画面用コントローラ
     */
    public function indexAction()
    {
        //サムネイル設定用
        $iiifViewersSetting = "iiifViewersSetting";

        // /admin/iiif-viewers
        $settings = $this->serviceLocator->get('Omeka\Settings');
        // POSTの場合は登録処理
        if ($this->getRequest()->isPost()) {
            $params = $this->getRequest()->getPost();
            $settings->set($iiifViewersSetting, $params);
        }
        // フォーム取得
        $form = $this->serviceLocator->get('FormElementManager')->get(IndexForm::class);
        // 設定データ取得
        $data = $settings->get($iiifViewersSetting, ['']);

        // フォームデータ設定
        $form->setData($data);
        // ビュー取得
        $viewModel = new ViewModel();
        // ビューにデータを設定
        $viewModel->setVariable('data', $data);
        // ビューにフォームを設定
        $viewModel->form = $form;
        return $viewModel;
    }

    /**
     * sidebarSelectAction
     *
     *　アイコンファイルアップロード、選択サイドバー用コントローラ
     */
    public function sidebarSelectAction()
    {
        $this->setBrowseDefaults('id');
        // アイコンデータ取得
        $response = $this->api()->search('iiif_viewers_icons', $this->params()->fromQuery());
        // ページネーション設定
        $this->paginator($response->getTotalResults());
        // ビュー取得
        $view = new ViewModel;
        // アイコン一覧データ設定
        $view->setVariable('iiif_viewers_icons', $response->getContent());
        // レイアウトテンプレートなし
        $view->setTerminal(true);
        return $view;
    }

    /**
     * addAction
     *
     * アイコン追加
     */
    public function addAction()
    {
        $httpResponse = $this->getResponse();
        $httpResponse->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        if ($this->getRequest()->isPost()) {
            $fileData = $this->getRequest()->getFiles()->toArray();
            try {
                // 登録
                $response = $this->api(null, true)->create('iiif_viewers_icons', [], $fileData);
                $httpResponse->setContent(json_encode([]));
            } catch (ValidationException $e) {
                $errors = [];
                $iterator = new RecursiveIteratorIterator(
                    new RecursiveArrayIterator(
                        $e->getErrorStore()->getErrors(),
                        RecursiveArrayIterator::CHILD_ARRAYS_ONLY
                    )
                );
                foreach ($iterator as $error) {
                    $errors[] = $this->translate($error);
                }
                $httpResponse->setContent(json_encode($errors));
                $httpResponse->setStatusCode(422);
            }
        } else {
            $httpResponse->setContent(json_encode([$this->translate('Icon uploads must be POSTed.')]));
            $httpResponse->setStatusCode(405);
        }

        return $httpResponse;
    }

    /**
     * deleteAction
     *
     * 削除
     */
    public function deleteAction()
    {
        if ($this->getRequest()->isPost()) {
            $params = $this->params()->fromPost();
            $iconId = $params['asset_id'];
            $icon = $this->api()->read('iiif_viewers_icons', $iconId)->getContent();
            // ファイル削除
            unlink(__DIR__ . '/../../../../../files/asset/' . $icon->filename());
            // データ削除
            $deleteResponse = $this->api()->delete('iiif_viewers_icons', $iconId);
        }
        $httpResponse = $this->getResponse();
        $httpResponse->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $httpResponse->setContent(json_encode([]));
        return $httpResponse;
    }
}
