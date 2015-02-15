<?php

class GroupsController extends Controller {
    public $layout = 'main';
    public function actionView($id) {
        $this->group = $this->loadModel($id);
        $this->survey = $this->group->survey;
        $this->render('view', ['group' => $this->group]);
    }
    
    protected function loadModel($id) {
        return QuestionGroup::model()->findByAttributes([
            'gid' => $id,
            'language' => App()->language
        ]);
    }
}