<?php
/**
* 文章模块
*
*
*/
class Article
{
    // 媒体文件
    const ATTACH_MEDIA=0;
    // 资源文件
    const ATTACH_FILE=1;
    
    /**
    * 添加文章资源
    */
    public static function addAttachment(int $id, int $attachment, int $type=self::ATTACH_MEDIA)
    {
        return \db\article\Attachment::create($id, $attachment, $type);
    }

    /**
    * 移除文章全部资源
    */
    public static function removeAttachments(int $id)
    {
        $attachments=\db\article\Attachment::getByArticle($id);
        if ($attachments) {
            try {
                Query::begin();
                foreach ($attachments as $attachment) {
                    self::removeAttachment($attachment['id'], $attachment['data']);
                }
                Query::commit();
            } catch (\Exception $e) {
                Query::rollBack();
                return false;
            }
        }
        return true;
    }



   
    protected static function removeAttachment(int $attachment, int $upload)
    {
        try {
            Query::begin();
            \db\Upload::delete($upload);
            \db\article\Attachment::delete($attachment);
            Query::commit();
        } catch (\Exception $e) {
            Query::rollBack();
            return false;
        }
        return true;
    }
}
