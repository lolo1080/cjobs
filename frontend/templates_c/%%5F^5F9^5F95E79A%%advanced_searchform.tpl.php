<?php /* Smarty version 2.6.19, created on 2013-05-01 01:26:04
         compiled from advanced_searchform.tpl */ ?>


          <form action="http://localhost/cjobs/jobs/" method="POST">
          <input type="hidden" name="search_type" value="advanced" />
          <table border="0" cellspacing="4" cellpadding="0">
            <!-- Keywords -->
            <tr>
              <td colspan="2"><span style="color:#720000;font-weight:bold;font-size:95%;">Keywords</span></td>
            </tr>

            <tr>
              <td nowrap><span style="font-size:72%;">With <b>all</b> of these words</span></td><td><input type="text" id="textinput" name="as_all" style="width: 280px; height:18px; background-color: #f9f9f9; border: 1px solid #999999;" /></td>
            </tr>

            <tr>
              <td nowrap><span style="font-size:72%;">With the <b>exact phrase</b></span></td><td><input type="text" id="textinput" name="as_phrase" style="width: 280px; height:18px; background-color: #f9f9f9; border: 1px solid #999999;" /></td>
            </tr>
            <tr>
              <td nowrap><span style="font-size:72%;">With <b>at least one</b> of these words&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td><td><input type="text" id="textinput" name="as_any" style="width: 280px; height:18px; background-color: #f9f9f9; border: 1px solid #999999;" /></td>
            </tr>
            <tr>
              <td nowrap><span style="font-size:72%;"><b>Without</b> the words</span></td><td><input type="text" id="textinput" name="as_not" style="width: 280px; height:18px; background-color: #f9f9f9; border: 1px solid #999999;" /></td>
            </tr>
            <tr>
              <td nowrap><span style="font-size:72%;">With these words in the <b>title</b></span></td><td><input type="text" id="textinput" name="as_title" style="width: 280px; height:18px; background-color: #f9f9f9; border: 1px solid #999999;" /></td>
            </tr>
            <tr>
              <td nowrap><span style="font-size:72%;">From this <b>company</b></span></td><td><input type="text" id="textinput" name="as_company" style="width: 280px; height:18px; background-color: #f9f9f9; border: 1px solid #999999;" /></td>
            </tr>

            <tr>
              <td colspan="2"><div style="clear: both;margin: 5px 10px; border-top: 1px solid #ccc; color: #333;"></div></td>
            </tr>

            <!-- Location -->
            <tr>
              <td colspan="2"><span style="color:#720000;font-weight:bold;font-size:95%;">Location</span></td>
            </tr>
            <tr>
              <td nowrap><span style="font-size:72%;"><b>City</b>, <b>State</b>, or <b>Zip</b></span></td><td><input type="text" id="textinput" name="where" style="width: 280px; height:18px; background-color: #f9f9f9; border: 1px solid #999999;" /></td>
            </tr>
						<?php if (( $this->_tpl_vars['AllowWithinSearch'] )): ?>
            <tr>
              <td nowrap><span style="font-size:72%;"><b>Within</b></span></td>
              <td>
                <select name="radius" style="width: 282px; height:22px; background-color: #f9f9f9; border: 1px solid #999999;">
                <?php $_from = $this->_tpl_vars['SearchRadiusList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
                  <option value="<?php echo $this->_tpl_vars['key']; ?>
"<?php echo $this->_tpl_vars['item']['selected']; ?>
><?php echo $this->_tpl_vars['item']['caption']; ?>
</option>
                <?php endforeach; endif; unset($_from); ?>
                </select>
              </td>
            </tr>
						<?php endif; ?>

            <tr>
              <td colspan="2"><div style="clear: both;margin: 5px 10px; border-top: 1px solid #ccc; color: #333;"></div></td>
            </tr>

            <!-- Job -->
            <tr>
              <td colspan="2"><span style="color:#720000;font-weight:bold;font-size:95%;">Job</span></td>
            </tr>
            <tr>
              <td nowrap><span style="font-size:72%;"><b>Related</b> to category</span></td>
              <td>
                <select name="jobs_category" style="width: 282px; height:22px; background-color: #f9f9f9; border: 1px solid #999999;">
                <?php $_from = $this->_tpl_vars['JobCategoriesList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
                  <option value="<?php echo $this->_tpl_vars['key']; ?>
"><?php echo $this->_tpl_vars['item']['cat_name']; ?>
</option>
                <?php endforeach; endif; unset($_from); ?>
                </select>
              </td>
            </tr>
            <tr>
              <td nowrap><span style="font-size:72%;">Show jobs of type</span></td>
              <td>
                <select name="jobs_type" style="width: 282px; height:22px; background-color: #f9f9f9; border: 1px solid #999999;">
                <?php $_from = $this->_tpl_vars['SearchJobTypesList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
                  <option value="<?php echo $this->_tpl_vars['key']; ?>
"<?php echo $this->_tpl_vars['item']['selected']; ?>
><?php echo $this->_tpl_vars['item']['caption']; ?>
</option>
                <?php endforeach; endif; unset($_from); ?>
                </select>
              </td>
            </tr>
            <tr>
              <td nowrap><span style="font-size:72%;">Show jobs from</span></td>
              <td>
                <select name="jobs_from" style="width: 282px; height:22px; background-color: #f9f9f9; border: 1px solid #999999;">
                <?php $_from = $this->_tpl_vars['SearchJobFromList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
                  <option value="<?php echo $this->_tpl_vars['key']; ?>
"<?php echo $this->_tpl_vars['item']['selected']; ?>
><?php echo $this->_tpl_vars['item']['caption']; ?>
</option>
                <?php endforeach; endif; unset($_from); ?>
                </select>
              </td>
            </tr>
            <tr>
              <td nowrap><span style="font-size:72%;"><label for="norecruiters">Exclude staffing agencies</label></span></td>
              <td><input id="norecruiters" type="checkbox" name="norecruiters" value="1" style="background-color: #f9f9f9; border: 1px solid #999999;"></td>
            </tr>
            <tr>
              <td nowrap><span style="font-size:72%;">Salary estimate</span></td><td><input type="text" id="textinput" name="salary" style="width: 232px; height:18px; background-color: #f9f9f9; border: 1px solid #999999;"><span style="font-size:72%;"> per year </span><br /><span style="font-size:67%;">$50,000 or $40K-$90K</span></td>
            </tr>
            <tr>
              <td nowrap><span style="font-size:72%;">Jobs published</span></td>
              <td>
                <select name="jobs_published" style="width: 282px; height:22px; background-color: #f9f9f9; border: 1px solid #999999;">
                <?php $_from = $this->_tpl_vars['SearchJobPublishedList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
                  <option value="<?php echo $this->_tpl_vars['key']; ?>
"<?php echo $this->_tpl_vars['item']['selected']; ?>
><?php echo $this->_tpl_vars['item']['caption']; ?>
</option>
                <?php endforeach; endif; unset($_from); ?>
                </select>
              </td>
            </tr>

            <tr>
              <td colspan="2"><div style="clear: both;margin: 5px 10px; border-top: 1px solid #ccc; color: #333;"></div></td>
            </tr>

            <!-- Preferences -->
            <tr>
              <td colspan="2"><span style="color:#720000;font-weight:bold;font-size:95%;">Preferences</span></td>
            </tr>
            <tr>
              <td nowrap><span style="font-size:72%;">Number results per page</span></td>
              <td>
                <select name="number_results" style="width: 282px; height:22px; background-color: #f9f9f9; border: 1px solid #999999;">
                <?php $_from = $this->_tpl_vars['SearchNumberResults']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
                  <option value="<?php echo $this->_tpl_vars['key']; ?>
"<?php echo $this->_tpl_vars['item']['selected']; ?>
><?php echo $this->_tpl_vars['item']['caption']; ?>
</option>
                <?php endforeach; endif; unset($_from); ?>
                </select>
              </td>
            </tr>
            <tr>
              <td nowrap><span style="font-size:72%;">Sort jobs by</span></td>
              <td>
                <select name="sort_by" style="width: 282px; height:22px; background-color: #f9f9f9; border: 1px solid #999999;">
                  <option selected value="">Relevance</option>
                  <option value="date">Date</option>
                </select>
              </td>
            </tr>

            <tr>
              <td colspan="2">&nbsp;</td>
            </tr>

            <tr>
              <td nowrap colspan="2">
                <input type="submit" value="Search jobs" class="searchButton " />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="reset" value="Clear" class="simpleButton" style="width:107px;" />
              </td>
            </tr>
          </table>
          </form>

