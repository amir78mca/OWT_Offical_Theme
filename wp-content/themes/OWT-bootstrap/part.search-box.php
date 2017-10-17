<?php
    $langcode = get_locale_lang_code();
    $s_langcode = $langcode=='zh' ? 'zh-cn' : $langcode;
    $s_label = __('Search UN site', 'site');
?>

<div class="search-form">
    <form method="get" action="https://search.un.org">
        <input name="query" value="" 
            id="search_input"
            placeholder="<?php echo esc_attr($s_label); ?>"
            lang="<?php echo $langcode; ?>"
            <?php if ($langcode == 'ar'){
                echo 'dir="rtl"';
            } else {
                echo 'dir="ltr"';
            }?> 
            type="text" 
            />
        <button type="submit" onclick="submit();"><?php echo esc_attr($s_label); ?></button>
        <input type="hidden" name="tpl" value="site"/>
        <input type="hidden" name="lang" value="<?php echo $s_langcode; ?>"/>
    </form>
    <div class="clear"></div>
</div>

