<?php

class Openstream_Osmm_Adminhtml_KeywordController extends Mage_Adminhtml_Controller_Action
{
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')
            ->isAllowed('system/tools_osmm');
    }

    public function indexAction()
    {
        $this->loadLayout()
            ->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

	public function editAction()
    {
        $model = Mage::getModel('osmm/query');
        Mage::register('current_osmm_keyword', $model);
        $id = $this->getRequest()->getParam('id');

        try{
            if($id){
                if(!$model->load($id)->getId()){
                    Mage::throwException($this->__('No record with ID "%s" found.', $id));
                }
            }

            $pageTitle = $model->getId() ? $this->__('Edit %s (%s)', $model->getData('query_q'), $model->getId()) : $this->__('New Keyword');
            $this->_title($pageTitle);
            $this->loadLayout();
            $this->renderLayout();
        }catch(Exception $e){
            Mage::logException($e);
            $this->_getSession()->addError($e->getMessage());
            $this->_redirect('*/*');
        }
	}

    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $this->_getSession()->setFormData($data);
            $model = Mage::getModel('osmm/query');
            /* @var $model Openstream_Osmm_Model_Query */
            $id = $this->getRequest()->getParam('id');

            try {
                if ($id) {
                    $model->load($id);
                    if($data['query_q'] != $model->getData('query_q') || $data['query_lang'] != $model->getData('query_lang') || $data['query_geocode'] != $model->getData('query_geocode')){
                        $model->clearStoredData();
                        $this->_getSession()->addNotice($this->__('All previously stored data for this keyword is wiped out.'));
                    }else{
                        Mage::throwException($this->__('Nothing to save. No modifications were made.'));
                    }
                }

                $model->addData($data)->save();

                if (!$model->getId()) {
                    Mage::throwException($this->__('Error saving keyword'));
                }

                $this->_getSession()->addSuccess($this->__('Keyword was successfully saved'));
                $this->_getSession()->setFormData(array());

                if ($this->getRequest()->getParam('back')) {
                    $params = array('id' => $model->getId());
                    $this->_redirect('*/*/edit', $params);
                } else {
                    $this->_redirect('*/*');
                }
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
                if ($model && $model->getId()) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                } else {
                    $this->_redirect('*/*/new');
                }
            }

            return;
        }

        $this->_getSession()->addError($this->__('No data found to save'));
        $this->_redirect('*/*');
    }

    public function deleteAction()
    {
        $model = Mage::getModel('osmm/query');
        /* @var $model Openstream_Osmm_Model_Query */
        $id = $this->getRequest()->getParam('id');

        try {
            if ($id) {
                if (!$model->load($id)->getId()) {
                    Mage::throwException($this->__('No record with ID "%s" found.', $id));
                }
                $name = $model->getData('query_q');
                $model->clearStoredData()
                      ->unbindFromAllProjects()
                      ->delete();
                $this->_getSession()->addSuccess($this->__('"%s" (ID %d) was successfully deleted', $name, $id));
            }
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()->addError($e->getMessage());
        }

        $this->_redirect('*/*');
    }

    public function massDeleteAction()
    {
        if ($query_ids = $this->getRequest()->getPost('query_id')) {
            $deleted = 0;
            $model = Mage::getModel('osmm/query');
            foreach ((array) $query_ids as $id) {
                $model->setId($id)
                      ->clearStoredData()
                      ->unbindFromAllProjects()
                      ->delete();
                $deleted++;
            }
            $this->_getSession()->addSuccess(
                $this->__('%s campaign(s) deleted.', $deleted)
            );
        }

        $this->_redirect('*/*');
    }
}