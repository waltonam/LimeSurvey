<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    /*
    * LimeSurvey
    * Copyright (C) 2013 The LimeSurvey Project Team / Carsten Schmitz
    * All rights reserved.
    * License: GNU/GPL License v2 or later, see LICENSE.php
    * LimeSurvey is free software. This version may have been modified pursuant
    * to the GNU General Public License, and as distributed it includes or
    * is derivative of works licensed under the GNU General Public License or
    * other free or open source software licenses.
    * See COPYRIGHT.php for copyright notices and details.
    *
       *	Files Purpose: lots of common functions
    */

/**
 * Class QuestionGroup
 * @property string $randomization_group
 * @property Question[] $questions
 */
    class QuestionGroup extends LSActiveRecord
    {
        public $before;
        /**
        * Returns the setting's table name to be used by the model
        *
        * @access public
        * @return string
        */
        public function tableName()
        {
            return '{{groups}}';
        }

        public function behaviors() {
            return array_merge(parent::behaviors(), [
                'translatable' => [
                    'class' => SamIT\Yii1\Behaviors\TranslatableBehavior::class,
                    'translationModel' => Translation::class,
                    'model' => 'Group', // See TranslatableBehavior comments.
                    'attributes' => ['group_name', 'description'],
                    'baseLanguage' => function(QuestionGroup $group) { return $group->survey->language; }
                ]
            ]);
        }

    /**
    * Returns this model's validation rules
    *
    */
    public function rules()
    {
        return [
            ['group_name,description','LSYii_Validators'],
            ['group_name', 'required']
        ];
    }

        /**
        * Defines the relations for this model
        *
        * @access public
        * @return array
        */
        public function relations()
        {
            return [
                'questions' => [self::HAS_MANY, Question::class, 'gid', 'on' => 'parent_qid = 0', 'order' => 'question_order'],
                'survey' => [self::BELONGS_TO, 'Survey', 'sid']
            ];
            
        }

        public function getQuestionCount() {
            return Question::model()->countByAttributes(['gid' => $this->id, 'parent_qid' => 0]);
        }

        function updateGroupOrder($sid,$lang,$position=0)
        {
            $data=Yii::app()->db->createCommand()->select('gid')
            ->where(array('and','sid=:sid','language=:language'))
            ->order('group_order, group_name ASC')
            ->from('{{groups}}')
            ->bindParam(':sid', $sid, PDO::PARAM_INT)
            ->bindParam(':language', $lang, PDO::PARAM_STR)
            ->query();

            $position = intval($position);
            foreach($data->readAll() as $row)
            {
                Yii::app()->db->createCommand()->update($this->tableName(),array('group_order' => $position),'gid='.$row['gid']);
                $position++;
            }
        }

        function getGroups($surveyid) {
            $language = Survey::model()->findByPk($surveyid)->language;
            return Yii::app()->db->createCommand()
            ->select(array('gid', 'group_name'))
            ->from($this->tableName())
            ->where(array('and', 'sid=:surveyid', 'language=:language'))
            ->order('group_order asc')
            ->bindParam(":language", $language, PDO::PARAM_STR)
            ->bindParam(":surveyid", $surveyid, PDO::PARAM_INT)
            ->query()->readAll();
        }

        public static function deleteWithDependency($groupId, $surveyId)
        {
            $questionIds = QuestionGroup::getQuestionIdsInGroup($groupId);
            Question::deleteAllById($questionIds);
            Assessment::model()->deleteAllByAttributes(array('sid' => $surveyId, 'gid' => $groupId));
            return QuestionGroup::model()->deleteAllByAttributes(array('sid' => $surveyId, 'gid' => $groupId));
        }

        /**
        * Get group description
        *
        * @param int iGroupId
        * @param string sLanguage
        */
        public function getGroupDescription($iGroupId, $sLanguage)
        {
            $solover = $this->findByPk(array('gid' => $iGroupId, 'language' => $sLanguage))->description;
            return $this->findByPk(array('gid' => $iGroupId, 'language' => $sLanguage))->description;
        }

        private static function getQuestionIdsInGroup($groupId) {
            $questions = Yii::app()->db->createCommand()
            ->select('qid')
            ->from('{{questions}} q')
            ->join('{{groups}} g', 'g.gid=q.gid AND g.gid=:groupid AND q.parent_qid=0')
            ->group('qid')
            ->bindParam(":groupid", $groupId, PDO::PARAM_INT)
            ->queryAll();

            $questionIds = array();
            foreach ($questions as $question) {
                $questionIds[] = $question['qid'];
            }

            return $questionIds;
        }


        /**
         * This function is here to support proper naming of entity attributes.
         * Since surveys have a title, I have decided all non-person entities have a title not a name.
         * @return string
         */
        public function getTitle() {
            return $this->group_name;
        }

        public function getDisplayLabel() {
            return $this->group_name;
        }
    }
?>
