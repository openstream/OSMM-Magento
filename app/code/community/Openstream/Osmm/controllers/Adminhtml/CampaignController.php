<?php

class Openstream_Osmm_Adminhtml_CampaignController extends Mage_Adminhtml_Controller_Action
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
        $model = Mage::getModel('osmm/project');
        Mage::register('current_osmm_project', $model);
        $id = $this->getRequest()->getParam('id');

        try{
            if($id){
                if(!$model->load($id)->getId()){
                    Mage::throwException($this->__('No record with ID "%s" found.', $id));
                }
            }

            $pageTitle = $model->getId() ? $this->__('Edit %s (%s)', $model->getData('project_name'), $model->getId()) : $this->__('New Campaign');
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
            $model = Mage::getModel('osmm/project');
            $id = $this->getRequest()->getParam('id');

            try {
                if ($id) {
                    $model->load($id);
                }

                if (isset($data['project_keywords'])) {
                    $keywords = explode(',', $data['project_keywords']);
                    $model->setPostedKeywords($keywords);
                }

                $model->addData($data)->save();

                if (!$model->getId()) {
                    Mage::throwException($this->__('Error saving campaign'));
                }

                $this->_getSession()->addSuccess($this->__('Campaign was successfully saved'));
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
        $model = Mage::getModel('osmm/project');
        $id = $this->getRequest()->getParam('id');

        try {
            if ($id) {
                if (!$model->load($id)->getId()) {
                    Mage::throwException($this->__('No record with ID "%s" found.', $id));
                }
                $name = $model->getData('project_name');
                $model->delete();
                $this->_getSession()->addSuccess($this->__('"%s" (ID %d) was successfully deleted', $name, $id));
            }
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()->addError($e->getMessage());
        }

        $this->_redirect('*/*');
    }

    public function massStatusAction()
    {
        $project_ids = $this->getRequest()->getParam('project_id');
        if(!is_array($project_ids)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($project_ids as $project_id) {
                	Mage::getSingleton('osmm/project')
                        ->load($project_id)
                        ->setData('project_status', $this->getRequest()->getParam('project_status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($project_ids))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*');
    }

    public function massDeleteAction()
    {
        if ($project_ids = $this->getRequest()->getPost('project_id')) {
            $deleted = 0;
            $model = Mage::getModel('osmm/project');
            foreach ((array) $project_ids as $id) {
                $model->setId($id)->delete();
                $deleted++;
            }
            $this->_getSession()->addSuccess(
                $this->__('%s campaign(s) deleted.', $deleted)
            );
        }

        $this->_redirect('*/*');
    }
}