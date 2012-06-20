<?php

class Openstream_Osmm_Model_Resource_Project extends Mage_Core_Model_Resource_Db_Abstract
{
    protected $_projectQueryTable;

    protected function _construct()
    {
        $this->_init('osmm/project', 'project_id');
        $this->_projectQueryTable = $this->getTable('osmm/projecttoquery');
    }

    /**
     * Process campaign data after save campaign object
     * save campaign/keyword relationships
     *
     * @param Openstream_Osmm_Model_Project $object
     * @return Openstream_Osmm_Model_Resource_Project
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $this->_saveProjectKeywords($object);
        return parent::_afterSave($object);
    }

    /**
     * Get array of keywords associated to campaign
     *
     * @param Openstream_Osmm_Model_Project $project
     * @return array
     */
    public function getKeywords($project)
    {
        $select = $this->_getWriteAdapter()->select()
            ->from($this->_projectQueryTable, array('query_id'))
            ->where('project_id = :project_id');
        $bind = array('project_id' => (int)$project->getId());

        return $this->_getWriteAdapter()->fetchCol($select, $bind);
    }

    /**
     * Save campaign/keyword relationships
     *
     * @param Openstream_Osmm_Model_Project $project
     * @return Openstream_Osmm_Model_Resource_Project
     */
    protected function _saveProjectKeywords($project)
    {
        $id = $project->getId();
        /**
         * new campaign/keyword relationships
         */
        $keywords = $project->getPostedKeywords();

        if ($keywords === null) {
            return $this;
        }

        /**
         * old campaign/keyword relationships
         */
        $oldKeywords = $project->getKeywords();

        $insert = array_diff($keywords, $oldKeywords);
        $delete = array_diff($oldKeywords, $keywords);

        $adapter = $this->_getWriteAdapter();

        /**
         * Delete keywords from project
         */
        if (!empty($delete)) {
            $cond = array(
                'query_id IN(?)' => $delete,
                'project_id=?' => $id
            );
            $adapter->delete($this->_projectQueryTable, $cond);
        }

        /**
         * Add keywords to project
         */
        if (!empty($insert)) {
            $data = array();
            foreach ($insert as $keyword) {
                $data[] = array(
                    'project_id' => (int)$id,
                    'query_id'  => (int)$keyword
                );
            }
            $adapter->insertMultiple($this->_projectQueryTable, $data);
        }

        return $this;
    }
}