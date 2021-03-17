<?php
namespace IiifViewers\Api\Adapter;

use Omeka\Api\Adapter\AbstractEntityAdapter;
use IiifViewers\Entity\IiifViewersIcon;
use IiifViewers\Api\Representation\IiifViewersIconRepresentation;
use Doctrine\ORM\QueryBuilder;
use Omeka\Api\Request;
use Omeka\Entity\EntityInterface;
use Omeka\File\Validator;
use Omeka\Stdlib\ErrorStore;

/**
 * IiifViewersIconAdapter
 * アイコンデータ用Adapter
 */
class IiifViewersIconAdapter extends AbstractEntityAdapter
{
    // アップロード可能ファイル
    const ALLOWED_MEDIA_TYPES = ['image/jpeg', 'image/png', 'image/gif', 'image/svg', 'image/svgz', 'image/svg+xml'];

    protected $sortFields = [
        'id' => 'id',
        'name' => 'name',
        'extension' => 'extension',
    ];

    public function getResourceName()
    {
        return 'iiif_viewers_icons';
    }

    public function getRepresentationClass()
    {
        return IiifViewersIconRepresentation::class;
    }

    public function getEntityClass()
    {
        return IiifViewersIcon::class;
    }

    public function buildQuery(QueryBuilder $qb, array $query)
    {
    }

    /**
     * hydrate
     *
     * テーブル操作
     * @param  mixed $request
     * @param  mixed $entity
     * @param  mixed $errorStore
     */
    public function hydrate(Request $request, EntityInterface $entity, ErrorStore $errorStore)
    {
        // データ作成処理
        if (Request::CREATE === $request->getOperation()) {
            // ファイルデータ取得
            $fileData = $request->getFileData();
            // ファイルがない場合はエラー
            if (!isset($fileData['file'])) {
                $errorStore->addError('file', 'No file was uploaded');
                return;
            }
            // アップロード
            $uploader = $this->getServiceLocator()->get('Omeka\File\Uploader');
            $tempFile = $uploader->upload($fileData['file'], $errorStore);
            // 一時ファイルがない場合は処理終了
            if (!$tempFile) {
                return;
            }
            // ファイル取得
            $tempFile->setSourceName($fileData['file']['name']);
            // アップロードファイル可能ファイルチェック
            $validator = new Validator(self::ALLOWED_MEDIA_TYPES);
            if (!$validator->validate($tempFile, $errorStore)) {
                return;
            }
            // アップロードファイル名取得
            $entity->setStorageId($tempFile->getStorageId());
            // 拡張子
            $entity->setExtension($tempFile->getExtension());
            // オリジナルファイル名
            $entity->setName($request->getValue('o:name', $fileData['file']['name']));
            // ファイル登録
            $tempFile->storeAsset();
            // 一時ファイル削除
            $tempFile->delete();
        } else {
            if ($this->shouldHydrate($request, 'o:name')) {
                $entity->setName($request->getValue('o:name'));
            }
        }
    }

    public function validateEntity(EntityInterface $entity, ErrorStore $errorStore)
    {
        // Don't add this name error if we have any other errors already
        if ($errorStore->hasErrors()) {
            return;
        }
        $name = $entity->getName();
        if (!is_string($name) || $name === '') {
            $errorStore->addError('o:name', 'An asset must have a name.');
        }
    }
}
