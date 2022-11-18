<?php


namespace fecbbc\services;


use fecshop\services\Service;
use yii\db\Query;

class Notices extends Service
{

    public $numPerPage = 10;

    const IN_READ = 1; // 已读

    const NOT_READ = -1; // 未读

    protected $_modelName = '\fecbbc\models\mysqldb\Notices';

    /**
     * @var \fecbbc\models\mysqldb\Notices
     */
    protected $_model;

    public function init()
    {
        parent::init();
        list($this->_modelName, $this->_model) = \Yii::mapGet($this->_modelName);
    }

    /**
     * 插入数据
     */
    public function insert( array &$data = [] ){
        if( empty( trim($data["to_id"]) ) ) {
            return false;
        }
        $data["create_at"] = time();
        $data["isread"] = self::NOT_READ;
        $this->_model->setAttributes($data , false);
        return $this->_model->insert();
    }

    /**
     * 设置消息已读
     */
    public function updateInRead( int $id = 0){
        $data["create_at"] = time();
        $this->_model->setAttributes($data , true);
        $notice = $this->_model::find()->where(["id"=> $id])->one();
        if ( empty( $notice ) ) return false;
        $notice->isread = self::IN_READ ; // 状态已读
        $notice->read_at = time(); // 查看时间
        return $notice->save();
    }

    /**
     * 获取通知
     */
    public function getNotices ( int $id = 0 , $isread = 0 ,$page = 1 ,$size = 15){
        if( empty( $id ) ) {
            return ["total"=>0 , "list"=>[]];
        }
        $where = [ "to_id"=>$id ];
        if( !empty($isread) ) {
            $where["isread"] = $isread;
        }
        $query1 = $this->_model->find()->where($where);
//        $where["to_id"] = 0;
//        $query2 = $this->_model->find()->where($where);
//
//        $query = $query1->union($query2);
//        $uniQuery = (new Query())->from( ['c' => $query] );
//        $sql = $query1->offset(($page - 1) * $size)->limit( $size )->createCommand()->getRawSql();
        $result = $query1->offset(($page - 1) * $size)->limit( $size )->all();
        $arr["list"] = $result;
        $arr["total"] = $query1->count();
        return $arr;
    }
}