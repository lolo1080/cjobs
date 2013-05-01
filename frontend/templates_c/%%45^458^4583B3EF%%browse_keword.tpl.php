<?php /* Smarty version 2.6.19, created on 2013-05-01 01:06:36
         compiled from browse_keword.tpl */ ?>


            <?php if ($this->_tpl_vars['BrowseKeywordListNavigation']): ?>
            <div style="padding-top: 7px; padding-bottom: 7px;">
            <span style="color:#333;font-weight:bold;font-size:90%;">Navigation:</span>
            <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['BrowseKeywordListNavigation']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
              <a href="<?php echo $this->_tpl_vars['BrowseKeywordListNavigation'][$this->_sections['i']['index']]['url']; ?>
" class="addbrowselink"><?php echo $this->_tpl_vars['BrowseKeywordListNavigation'][$this->_sections['i']['index']]['keyword']; ?>
</a><?php if (! $this->_sections['i']['last']): ?> : <?php endif; ?>
            <?php endfor; endif; ?>
            </div>
            <?php endif; ?>

            <?php if ($this->_tpl_vars['BrowseKeywordListMostPopular']): ?>
            <div style="padding-top: 3px; padding-bottom: 7px;">
            <span style="color:#333;font-weight:bold;font-size:90%;">Most Popular:</span>
            <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['BrowseKeywordListMostPopular']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
              <a href="<?php echo $this->_tpl_vars['BrowseKeywordListMostPopular'][$this->_sections['i']['index']]['url']; ?>
" class="addbrowselink"><?php echo $this->_tpl_vars['BrowseKeywordListMostPopular'][$this->_sections['i']['index']]['keyword']; ?>
</a><?php if (! $this->_sections['i']['last']): ?>, <?php endif; ?>
            <?php endfor; endif; ?>
            </div>
            <?php endif; ?>

            <table width="100%" border="0">

            <tr>
            <?php $_from = $this->_tpl_vars['BrowseKeywordList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['main_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['main_list']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['section_key'] => $this->_tpl_vars['section_item']):
        $this->_foreach['main_list']['iteration']++;
?>
              <td valign="top">
                <table cellpadding="0" cellspacing="0" width="100%" border="0">
                <?php $_from = $this->_tpl_vars['section_item']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['block_key'] => $this->_tpl_vars['block_item']):
?>
                  <?php $_from = $this->_tpl_vars['block_item']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
                    <?php if ($this->_tpl_vars['key'] == 'main'): ?>
                      <tr>
                        <td class="browse_k0"><?php echo $this->_tpl_vars['item']['link']; ?>
</td>
                      </tr>
                    <?php else: ?>
                      <?php $_from = $this->_tpl_vars['item']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key1'] => $this->_tpl_vars['item1']):
?>
                        <tr>
                          <td><a href="<?php echo $this->_tpl_vars['item1']['url']; ?>
" class="browselink"><?php echo $this->_tpl_vars['item1']['link']; ?>
</a></td>
                        </tr>
                      <?php endforeach; endif; unset($_from); ?>
                    <?php endif; ?>
                  <?php endforeach; endif; unset($_from); ?>
                <?php endforeach; endif; unset($_from); ?>
                </table>
              </td>
            <?php endforeach; endif; unset($_from); ?>

            </tr>
            </table>

