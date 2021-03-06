<?php PrepareEditorScript(true, $this); ?>
<?php $this->renderPartial("./survey/Question/question_subviews/_ajax_variables", $ajaxDatas); ?>



<div class="side-body" id="edit-question-body">

    <!-- Page Title-->
    <h3>
        <?php
                if ($adding)
                {
                    eT("Add a new question");
                }
                elseif ($copying)
                {
                    eT("Copy question");
                }
                else
                {
                    eT("Edit question");
                }
        ?>
    </h3>

    <div class="row">
        <!-- Form for the whole page-->
        <?php echo CHtml::form(array("admin/database/index"), 'post',array('class'=>'form30 form-horizontal','id'=>'frmeditquestion','name'=>'frmeditquestion')); ?>

            <?php // if(!$adding):?>

                <!-- The tabs & tab-fanes -->
                <div class="col-lg-8 col-md-6 col-sm-5 content-right">
                    <?php if($adding):?>
                        <?php
                            $this->renderPartial(
                                './survey/Question/question_subviews/_tabs',
                                array(
                                    'eqrow'=>$eqrow,
                                    'addlanguages'=>$addlanguages,
                                    'surveyid'=>$surveyid,
                                    'gid'=>NULL, 'qid'=>NULL,
                                    'adding'=>$adding,
                                    'aqresult'=>$aqresult,
                                    'action'=>$action
                                )
                            ); ?>
                    <?php else:?>
                        <?php
                            $this->renderPartial(
                                './survey/Question/question_subviews/_tabs',
                                array(
                                    'eqrow'=>$eqrow,
                                    'addlanguages'=>$addlanguages,
                                    'surveyid'=>$surveyid,
                                    'gid'=>$gid, 'qid'=>$qid,
                                    'adding'=>$adding,
                                    'aqresult'=>$aqresult,
                                    'action'=>$action
                                )
                            ); ?>

                    <?php endif;?>
                </div>

                <!-- The Accordion -->
                <div class="col-lg-4 col-md-6 col-sm-7" id="accordion-container" style="background-color: #fff;">
                    <?php // TODO : find why the $groups can't be generated from controller?>
                    <div id='questionbottom'>
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

                            <!-- Copy options -->
                            <?php if ($copying): ?>
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingZero">
                                        <h4 class="panel-title">
                                            <a class="btn btn-default btn-xs hide-button hidden-xs opened handleAccordion">
                                                <span class="glyphicon glyphicon-chevron-left"></span>
                                            </a>
                                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseZero" aria-expanded="false" aria-controls="collapseZero">
                                                <?php eT("Copy options"); ?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseZero" class="panel-collapse collapse  in" role="tabpanel" aria-labelledby="headingTwo">
                                        <div class="panel-body">
                                            <div  class="form-group">
                                                <label class="col-sm-4 control-label" for='copysubquestions'><?php eT("Copy subquestions?"); ?></label>
                                                <div class="col-sm-8">
                                                    <input type='checkbox' class='checkboxbtn' checked='checked' id='copysubquestions' name='copysubquestions' value='Y' />
                                                </div>
                                            </div>
                                            <div  class="form-group">
                                                <label class="col-sm-4 control-label" for='copyanswers'><?php eT("Copy answer options?"); ?></label>
                                                <div class="col-sm-8">
                                                    <input type='checkbox' class='checkboxbtn' checked='checked' id='copyanswers' name='copyanswers' value='Y' />
                                                </div>
                                            </div>
                                            <div  class="form-group">
                                                <label class="col-sm-4 control-label" for='copyattributes'><?php eT("Copy advanced settings?"); ?></label>
                                                <div class="col-sm-8">
                                                    <input type='checkbox' class='checkboxbtn' checked='checked' id='copyattributes' name='copyattributes' value='Y' />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; // Copying ?>

                            <!-- General Options -->
                            <div class="panel panel-default" id="questionTypeContainer">

                                <!-- General Options : Header  -->
                                <div class="panel-heading" role="tab" id="headingOne">
                                    <h4 class="panel-title">
                                        <a class="btn btn-default btn-xs hide-button hidden-xs handleAccordion">
                                            <span class="glyphicon glyphicon-chevron-left"></span>
                                        </a>
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            <?php eT("General options");?>
                                        </a>
                                    </h4>
                                </div>

                                <div id="collapseOne" class="panel-collapse collapse <?php if (!$copying){echo ' in '; } ?>" role="tabpanel" aria-labelledby="headingOne">
                                    <div class="panel-body">
                                        <div>
                                            <div  class="form-group">
                                                <label class="col-sm-4 control-label" for="question_type_button">
                                                    <?php
                                                        eT("Question type:");
                                                    ?>
                                                </label>

                                                <?php
                                                    foreach (getQuestionTypeList($eqrow['type'], 'array') as $key=> $questionType)
                                                    {
                                                        if (!isset($groups[$questionType['group']]))
                                                        {
                                                            $groups[$questionType['group']] = array();
                                                        }
                                                        $groups[$questionType['group']][$key] = $questionType['description'];
                                                    }
                                                ?>

                                                <?php if(isset($selectormodeclass) && $selectormodeclass != "none" && $activated != "Y"): ?>
                                                    <input type="hidden" id="question_type" name="type" value="<?php echo $eqrow['type']; ?>" />
                                                    <div class="col-sm-8 btn-group" id="question_type_button" style="z-index: 1000">
                                                        <button type="button" class="btn btn-default dropdown-toggle " <?php if ($activated == "Y"){echo " disabled ";} ?>  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="z-index: 1000">
                                                            <?php foreach($groups as $name => $group):?>
                                                                <?php foreach($group as $type => $option):?>
                                                                    <?php if($type == $eqrow['type']):?>
                                                                        <span class="buttontext">
                                                                            <?php echo $option; ?>
                                                                            <?php if(YII_DEBUG):?>
                                                                                <em class="small">
                                                                                    Type code: <?php echo $type; ?>
                                                                                </em>
                                                                            <?php endif;?>
                                                                        </span>
                                                                    <?php endif; ?>
                                                                <?php endforeach;?>
                                                            <?php endforeach;?>
                                                            &nbsp;&nbsp;&nbsp;
                                                            <span class="caret"></span>
                                                        </button>

                                                        <ul class="dropdown-menu" style="z-index: 1000">

                                                            <?php foreach($groups as $name => $group):?>
                                                                <small><?php echo $name;?></small>

                                                                <?php foreach($group as $type => $option):?>
                                                                    <li>
                                                                        <a href="#" class="questionType" data-value="<?php echo $type; ?>" <?php if($type == $eqrow['type']){echo 'active';}?>><?php echo $option;?></a>
                                                                        <?php if(Yii::app()->getConfig("debug")===2):?>
                                                                            <em class="small text-info col-sm-offset-1">
                                                                                question type code: <?php echo $type; ?>
                                                                            </em>
                                                                        <?php endif;?>
                                                                    </li>
                                                                <?php endforeach;?>

                                                                <li role="separator" class="divider"></li>
                                                            <?php endforeach;?>
                                                        </ul>
                                                    </div>
                                                <?php elseif(isset($selectormodeclass) && $selectormodeclass == "none" && $activated != "Y"): ?>
                                                    <div class="col-sm-8 btn-group" id="question_type_button" style="z-index: 1000">
                                                        <?php
                                                            $aQtypeData=array();
                                                            foreach (getQuestionTypeList($eqrow['type'], 'array') as $key=> $questionType)
                                                            {
                                                                $aQtypeData[]=array('code'=>$key,'description'=>$questionType['description'],'group'=>$questionType['group']);
                                                            }
                                                            echo CHtml::dropDownList(
                                                                                        'type',
                                                                                        'category',
                                                                                        CHtml::listData($aQtypeData,'code','description','group'),
                                                                                        array(
                                                                                                'class' => 'form-control',
                                                                                                'id'=>'question_type',
                                                                                                'options' => array($eqrow['type']=>array('selected'=>true))
                                                                                            )
                                                                                        );
                                                                                        ?>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="col-sm-8 ">
                                                        <p style="padding-top: 7px;">
                                                                <?php foreach($groups as $name => $group):?>
                                                                    <?php foreach($group as $type => $option):?>
                                                                        <?php if($type == $eqrow['type']){echo '' . $option . '';}?>
                                                                    <?php endforeach;?>
                                                                <?php endforeach;?>
                                                        </p>
                                                    </div>

                                                <?php endif; ?>
                                            </div>

                                            <div  class="form-group">
                                                <label class="col-sm-4 control-label" for='gid'><?php eT("Question group:"); ?></label>
                                                <div class="col-sm-8">
                                                    <select name='gid' id='gid' class="form-control" <?php if ($activated == "Y"){echo " disabled ";} ?> >
                                                        <?php echo getGroupList3($eqrow['gid'],$surveyid); ?>
                                                    </select>
                                                    <?php if ($activated == "Y"): ?>
                                                        <input type='hidden' name='gid' value='<?php echo $eqrow['gid'];?>' />
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <div  class="form-group" id="OtherSelection">
                                                <label class="col-sm-4 control-label"><?php eT("Option 'Other':"); ?></label>
                                                <?php if ($activated != "Y"): ?>
                                                    <div class="col-sm-8">
                                                        <?php $this->widget('yiiwheels.widgets.switch.WhSwitch', array('name' => 'other', 'value'=> $eqrow['other'] === "Y", 'onLabel'=>gT('On'),'offLabel'=>gT('Off')));?>
                                                    </div>
                                                <?php else:?>
                                                    <?php eT("Cannot be changed (survey is active)");?>
                                                    <input type='hidden' name='other' value="<?php echo $eqrow['other']; ?>" />
                                                <?php endif;?>
                                            </div>

                                            <div id='MandatorySelection' class="form-group">
                                                <label class="col-sm-4 control-label"><?php eT("Mandatory:"); ?></label>
                                                <div class="col-sm-8">
                                                    <!-- Todo : replace by direct use of bootstrap switch. See statistics -->
                                                    <?php $this->widget('yiiwheels.widgets.switch.WhSwitch', array('name' => 'mandatory', 'value'=> $eqrow['mandatory'] === "Y", 'onLabel'=>gT('On'),'offLabel'=>gT('Off')));?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-4 control-label" for='relevance'><?php eT("Relevance equation:"); ?></label>
                                                <div class="col-sm-8">
                                                    <textarea class="form-control" rows='1' id='relevance' name='relevance' <?php if ($eqrow['conditions_number']) {?> readonly='readonly'<?php } ?> ><?php echo $eqrow['relevance']; ?></textarea>
                                                    <?php if ($eqrow['conditions_number']) :?>
                                                        <span class='annotation'> <?php eT("Note: You can't edit the relevance equation because there are currently conditions set for this question."); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <div id='Validation'  class="form-group">
                                                <label class="col-sm-4 control-label" for='preg'><?php eT("Validation:"); ?></label>
                                                <div class="col-sm-8">
                                                    <input class="form-control" type='text' id='preg' name='preg' size='50' value="<?php echo $eqrow['preg']; ?>" />
                                                </div>
                                            </div>

                                            <?php if ($adding): ?>
                                                <div id='Position' class='form-group'>
                                                    <label class="col-sm-4 control-label" for='pos'><?php eT("Position:"); ?></label>
                                                    <div class="col-sm-8">
                                                        <select class='form-control' name='questionposition' id='questionposition'>
                                                            <option value=''><?php eT("At end"); ?></option>
                                                            <option value='0'><?php eT("At beginning"); ?></option>
                                                            <?php foreach ($oqresult as $oq): ?>
                                                                <option value='<?php echo $oq->attributes['question_order'] + 1; ?>'><?php eT("After"); ?>: <?php echo $oq->attributes['title']; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Advanced settings -->
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingTwo">
                                    <h4 class="panel-title">
                                        <a class="btn btn-default btn-xs hide-button hidden-xs opened handleAccordion">
                                            <span class="glyphicon glyphicon-chevron-left"></span>
                                        </a>
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            <?php eT("Advanced settings"); ?>
                                        </a>
                                    </h4>
                                </div>

                                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                    <div class="panel-body">
                                        <div id="advancedquestionsettingswrapper" >
                                            <div class="loader">
                                                <?php eT("Loading..."); ?>
                                            </div>

                                            <div id="advancedquestionsettings">
                                                <!-- Content append via ajax -->
                                            </div>
                                        </div>

                                        <br />
                                        <br/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if ($adding): ?>
                    <input type='hidden' name='action' value='insertquestion' />
                    <input type='hidden' id='sid' name='sid' value='<?php echo $surveyid; ?>' />
                    <p><input type='submit'  class="hidden" value='<?php eT("Add question"); ?>' />
                <?php elseif ($copying): ?>
                    <input type='hidden' name='action' value='copyquestion' />
                    <input type='hidden' id='oldqid' name='oldqid' value='<?php echo $qid; ?>' />
                    <p><input type='submit'  class="hidden" value='<?php eT("Copy question"); ?>' />
                <?php else: ?>
                    <input type='hidden' name='action' value='updatequestion' />
                    <input type='hidden' id='qid' name='qid' value='<?php echo $qid; ?>' />
                    <p><button type='submit' class="saveandreturn hidden" name="redirection" value="edit"><?php eT("Save") ?> </button>
                    <input type='submit'  class="hidden" value='<?php eT("Save and close"); ?>' />
                <?php endif; ?>
                <input type='hidden' id='sid' name='sid' value='<?php echo $surveyid; ?>' />
            <?php // endif;?>
        </form>
    </div>
</div>
