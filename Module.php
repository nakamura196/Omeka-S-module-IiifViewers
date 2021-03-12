<?php declare(strict_types=1);

namespace IiifViewers;

use Omeka\Module\AbstractModule;
use IiifViewers\Form\ConfigForm;
use Laminas\Mvc\Controller\AbstractController;
use Laminas\EventManager\Event;
use Laminas\EventManager\SharedEventManagerInterface;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Omeka\Module\Exception\ModuleCannotInstallException;
use Omeka\Stdlib\Message;

class Module extends AbstractModule
{
        
    /**
     * onBootstrap
     *
     * 起動処理
     * @param  mixed $event
     * @return void
     */
    public function onBootstrap(MvcEvent $event)
    {
        parent::onBootstrap($event);
        $acl = $this->getServiceLocator()->get('Omeka\Acl');
        // ログインなしで使用するAdapterとEntityを設定する。
        $acl->allow(
            null,
            [
                \IiifViewers\Api\Adapter\IiifViewersIconAdapter::class,
                \IiifViewers\Entity\IiifViewersIcon::class,
            ]);
    }
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    /**
     * getConfigForm
     *
     * 設定フォーム
     * @param  mixed $renderer
     * @return void
     */
    public function getConfigForm(PhpRenderer $renderer)
    {
        $translate = $renderer->plugin('translate');

        $services = $this->getServiceLocator();
        // 設定内容取得
        $settings = $services->get('Omeka\Settings');
        $form = $services->get('FormElementManager')->get(ConfigForm::class);
        $data = $settings->get('iiifviewers', ['']);
        $form->init();
        // フォームにデータを設定する
        $form->setData($data); //$params
        $html = $renderer->formCollection($form);
        return '<p>'
            . $translate('Please set urls of viewers.') // @translate
            . '</p>'
            . $html; //parent::getConfigForm($renderer);//$html;
    }
    
    /**
     * handleConfigForm
     *
     * 設定フォーム送信時
     * @param  mixed $controller
     * @return void
     */
    public function handleConfigForm(AbstractController $controller)
    {
        $services = $this->getServiceLocator();
        $settings = $services->get('Omeka\Settings');
        // $form = $services->get('FormElementManager')->get(ConfigForm::class);

        $params = $controller->getRequest()->getPost();

        // $form->init();
        // $form->setData($params);

        //以下の違いがわからない
        /*
        if (!$form->isValid()) {
            $controller->messenger()->addErrors($form->getMessages());
            return false;
        }
        */

        // $form->isValid();
        // $params = $form->getData();
        // 設定データ反映
        $settings->set('iiifviewers', $params);
    }

    public function attachListeners(SharedEventManagerInterface $sharedEventManager): void
    {
        $sharedEventManager->attach(
            'Omeka\Controller\Site\Item',
            'view.show.after',
            [$this, 'handleViewShowAfterItem']
        );
    }

    public function handleViewShowAfterItem(Event $event): void
    {
        $view = $event->getTarget();
        echo $view->IiifViewers($view->item);
    }
    /**
     * install
     * インストールで実行する処理
     * 
     * @param ServiceLocatorInterface $services
     * @return void
     */
    public function install(ServiceLocatorInterface $services): void
    {
        $translator = $services->get('MvcTranslator');
        // サービスをメンバー変数に設定する
        $this->setServiceLocator($services);
        // 依存モジュールチェック
        if (!$this->checkDependencies()) {
            $message = new Message(
                $translator->translate('This module requires modules "%s".'), // @translate
                implode('", "', $this->dependencies)
            );
            throw new ModuleCannotInstallException((string) $message);
        }
        // 後処理を実行する
        $this->postInstall($services);
    }
    /**
     * 依存モジュールチェック
     *
     * @return bool
     */
    protected function checkDependencies(): bool
    {
        // モジュール設定取得
        $config = $this->getConfig();
        // 依存モジュール取得
        $this->dependencies = $config['dependencies'];
        // 依存モジュールが存在しない、または全てアクティブの場合はtrue
        return empty($this->dependencies) || $this->areModulesActive($this->dependencies);

    }
    /**
     * areModulesActive
     * 
     * 依存モジュールがアクティブかどうかチェック
     * @param array $modules
     * @return bool
     */
    protected function areModulesActive(array $modules): bool
    {
        $services = $this->getServiceLocator();
        /** @var \Omeka\Module\Manager $moduleManager */
        $moduleManager = $services->get('Omeka\ModuleManager');
        foreach ($modules as $module) {
            $module = $moduleManager->getModule($module);
            // アクティブでない場合はfalse
            if (!$module || $module->getState() !== \Omeka\Module\Manager::STATE_ACTIVE) {
                return false;
            }
        }
        return true;
    }    
    /**
     * postInstall
     *
     * インストール後処理
     * @param  mixed $services
     * @return void
     */
    protected function postInstall(ServiceLocatorInterface $services): void
    {
        // 設定追加
        $this->manageSetting('install');
    }
    
     /**
     * unistall
     * アンインストールで実行する処理
     *
     * @param ServiceLocatorInterface $services
     * @return void
     */
    public function uninstall(ServiceLocatorInterface $services): void
    {
        // 設定を削除する
        $this->manageSetting('unistall');

    }
    /**
     * createTables
     * 
     * アイコン管理テーブル追加
     */
    protected function createTables()
    {
        // サービス取得
        $services = $this->getServiceLocator();
        $connection = $services->get('Omeka\Connection');
        $sql = <<<'SQL'
CREATE TABLE `iiif_viewers_icon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `storage_id` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `extension` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IIIF_VIEWER_STORAGE_ID` (`storage_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SQL;
        // テーブル作成
        $connection->exec($sql);
    }
    /**
     * dropTables
     * アイコン管理テーブル削除
     */
    protected function dropTables()
    {
        // サービス取得
        $services = $this->getServiceLocator();
        $connection = $services->get('Omeka\Connection');
        $sql = <<<'SQL'
DROP TABLE  `iiif_viewers_icon`;

SQL; 
        // テーブル削除
        $connection->exec($sql);
    }
        
    /**
     * getDefaultIcon
     * 
     * デフォルトIconパス取得
     * @param  mixed $fileName
     * @return void
     */
    protected function getDefaultIcon($fileName)
    {
        $imageDir = __DIR__ . '/asset/img/';
        return $imageDir . $fileName;
    }
    
    /**
     * files/assetディレクトリ保存先設定
     *
     * @param  mixed $file
     * @return void
     */
    protected function getIconDestination($file)
    {
        $imageDir = __DIR__ . '/../../files/asset';
        if (!file_exists($imageDir))
        {
            mkdir($imageDir);
        }
        $imageDir .= '/';
        $pathInfo = pathinfo($file);
        $storageId = uniqid("iiifviewers");
        return ['path' => $imageDir, 'storage_id' => $storageId, 'extension' => $pathInfo['extension']];
    }
    
    /**
     * Icon元ファイルと複製先設定
     *
     * @param  mixed $fileName
     * @return void
     */
    protected function getIconFileInfo($fileName)
    {
        $source = $this->getDefaultIcon($fileName);
        $destination = $this->getIconDestination($fileName);
        // 初期アイコンをfile/assetにコピー
        copy($source, $destination['path'] . $destination['storage_id'] . '.' . $destination['extension']);
        return ['source' => $source, 
            'destination' => $destination['path'], 
            'storage_id' => $destination['storage_id'],
            'extension' => $destination['extension']];
    }
        
    /**
     * 初期アイコンデータ登録
     *
     * @param  mixed $fileName
     * @return void
     */
    protected function setDefaultIcon($fileName)
    {
        $iconInfo = $this->getIconFileInfo($fileName);
        $data = ['name' => $fileName,
                'storage_id' => $iconInfo['storage_id'],
                'extension' => $iconInfo['extension']
            ];
        // サービス取得
        $services = $this->getServiceLocator();
        $connection = $services->get('Omeka\Connection');
        $connection->insert('iiif_viewers_icon', $data);
        $sql = 'select id from iiif_viewers_icon where storage_id = ?';
        $params = [$iconInfo['storage_id']];
        $result = $connection->fetchAll($sql, $params);
        return $result[0]['id'];
    }
    /**
     * setInitData
     * 
     * 初期アイコンデータ設定
     * @param  mixed $defaultSetting
     * @return void
     */
    protected function setInitData($defaultSetting)
    {
        // ロゴ設定
        $logo = $defaultSetting['icons']['logo'];
        // Iconファイル登録
        $logoIconId = $this->setDefaultIcon($logo);
        // Miradorアイコン設定
        $mirador = $defaultSetting['icons']['iiifviewers_mirador_icon'];
        // Iconファイル登録
        $miradorId = $this->setDefaultIcon($mirador);
        // UniversalViewerアイコン設定
        $universalViewer = $defaultSetting['icons']['iiifviewers_universal_viewer_icon'];
        // Iconファイル登録
        $universalViewerId = $this->setDefaultIcon($universalViewer);
        // CurationViewerアイコン設定
        $curationViewer = $defaultSetting['icons']['iiifviewers_curation_viewer_icon'];
        // Iconファイル登録
        $curationViewerId = $this->setDefaultIcon($curationViewer);
        // Tifyアイコン設定
        $tify = $defaultSetting['icons']['iiifviewers_tify_icon'];
        // Iconファイル登録
        $tifyId = $this->setDefaultIcon($tify);
        // 設定データ作成
        $data = $defaultSetting['url'];
        $data['logo'] = $logoIconId;
        $data['iiifviewers_mirador_icon'] = $miradorId;
        $data['iiifviewers_universal_viewer_icon'] = $universalViewerId;
        $data['iiifviewers_curation_viewer_icon'] = $curationViewerId;
        $data['iiifviewers_tify_icon'] = $tifyId;
        return $data;
    }
        
    /**
     * removeIconFiles
     * 
     * アイコンファイル削除
     * @return void
     */
    protected function removeIconFiles()
    {
        $imageDir = __DIR__ . '/../../files/asset/';
        $sql = 'select storage_id, extension from iiif_viewers_icon';
        $services = $this->getServiceLocator();
        $connection = $services->get('Omeka\Connection');
        // アイコンファイル抽出
        $result = $connection->fetchAll($sql);
        // アイコンファイルを削除
        foreach($result as $file)
        {
            $target = $imageDir . $file['storage_id'] . '.' . $file['extension'];
            unlink($target);
        }
    }
    /**
     * manageSetting
     * 
     * 設定を追加、削除する
     * @param [type] $type
     * @return void
     */
    private function manageSetting($type): void
    {
        // サービス取得
        $services = $this->getServiceLocator();
        $settings = $services->get('Omeka\Settings');
        // モジュール設定取得
        $config = $this->getConfig();
        // 設定値取得
        $defaultSettings = $config['iiifviewers']['config'];
        switch ($type)
        {
            // インストール時の追加処理
            case 'install':
                // アイコン管理テーブル追加
                $this->createTables();
                // 初期データ登録
                $settingData = $this->setInitData($defaultSettings);
                // 設定
                $settings->set('iiifviewers', $settingData);
                break;
            case 'unistall':
                // ファイル削除
                $this->removeIconFiles();
                // 設定削除
                $settings->delete('iiifviewers');
                // テーブル削除
                $this->dropTables();
                break;
        }
    }
}
