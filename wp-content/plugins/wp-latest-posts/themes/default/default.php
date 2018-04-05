<?php
$titleChecked = "";
$textChecked = "";
$dateChecked = "";
$categoryChecked = "";
$authorChecked = "";
$readMoreChecked = "";
$imageChecked = "";

if (isset($settings['dfTitle']) && (!empty($settings['dfTitle']))) {
    $titleChecked = "checked";
}
if (isset($settings['dfText']) && (!empty($settings['dfText']))) {
    $textChecked = "checked";
}
if (isset($settings['dfDate']) && (!empty($settings['dfDate']))) {
    $dateChecked = "checked";
}
if (isset($settings['dfCategory']) && (!empty($settings['dfCategory']))) {
    $categoryChecked = "checked";
}
if (isset($settings['dfAuthor']) && (!empty($settings['dfAuthor']))) {
    $authorChecked = "checked";
}
if (isset($settings['dfReadMore']) && (!empty($settings['dfReadMore']))) {
    $readMoreChecked = "checked";
}
if (isset($settings['dfThumbnail']) && (!empty($settings['dfThumbnail']))) {
    $imageChecked = "checked";
}
$dfThumbnailPositionTop = "";
$dfThumbnailPositionLeft = "";
$dfThumbnailPositionRight = "";
if (isset($settings['dfThumbnailPosition']) && (!empty($settings['dfThumbnailPosition']))) {
    if ($settings['dfThumbnailPosition'] == 'top') {
        $dfThumbnailPositionTop = "selected";
    }
    if ($settings['dfThumbnailPosition'] == 'left') {
        $dfThumbnailPositionLeft = "selected";
    }
    if ($settings['dfThumbnailPosition'] == 'right') {
        $dfThumbnailPositionRight = "selected";
    }
} else {
    $dfThumbnailPositionTop = "selected";
}

/**
 *  new setting theme default
 */
echo '<div id="wplp_config_zone_new" class="wpcu-inner-admin-block with-title with-border ' .
    $classdisabled . $classdisabledsmooth . '">';
echo '<h4>' . __('A new item', 'wp-latest-posts') . '</h4>';
echo '<div class="wplp-drag-config"></div>';
echo '<div class="imagePosition input-field input-select">';
echo '<label for="dfThumbnailPosition">' . __('Image Position', 'wp-latest-posts') . '</label>';
echo '<select id="dfThumbnailPosition" name="wplp_dfThumbnailPosition">
              <option ' . $dfThumbnailPositionTop . ' value="top">' . __('Top', 'wp-latest-posts') . '</option>
              <option ' . $dfThumbnailPositionLeft . ' value="left">' . __('Left', 'wp-latest-posts') . '</option>
              <option ' . $dfThumbnailPositionRight . ' value="right"> ' . __('Right', 'wp-latest-posts') . '</option>
          </select>';
echo '</div>';
echo '<div class="arrow_col_wrapper"><ul class="arrow_col">';

/**
 * display image field
 */
echo '<input type="hidden" name="wplp_dfThumbnail" value="">';
echo '<input id="dfThumbnail" ' . $imageChecked . ' type="checkbox" name="wplp_dfThumbnail" value="' .
    htmlspecialchars("Thumbnail") . '">';
echo '<label for="dfThumbnail">' . __('Thumbnail', 'wp-latest-posts') . '</label>' . '<br/>';


/**
 * display title field
 */
echo '<input type="hidden" name="wplp_dfTitle" value="">';
echo '<input id="dfTitle" ' . $titleChecked . ' type="checkbox" name="wplp_dfTitle" value="' .
    htmlspecialchars("Title") . '">';
echo '<label for="dfTitle">' . __('Title', 'wp-latest-posts') . '</label>' . '<br/>';

/**
 * display author field
 */
echo '<input type="hidden" name="wplp_dfAuthor" value="">';
echo '<input id="dfAuthor"' . $authorChecked . ' type="checkbox" name="wplp_dfAuthor" value="' .
    htmlspecialchars("Author") . '">';
echo '<label for="dfAuthor">' . __('Author', 'wp-latest-posts') . '</label>' . '<br/>';

/**
 * display date field
 */
echo '<input type="hidden" name="wplp_dfDate" value="">';
echo '<input id="dfDate"' . $dateChecked . ' type="checkbox" name="wplp_dfDate" value="' .
    htmlspecialchars("Date") . '">';
echo '<label for="dfDate">' . __('Date', 'wp-latest-posts') . '</label>' . '<br/>';

/**
 * display category field
 */
echo '<input type="hidden" name="wplp_dfCategory" value="">';
echo '<input id="dfCategory" ' . $categoryChecked . ' type="checkbox" name="wplp_dfCategory" value="' .
    htmlspecialchars("Category") . '">';
echo '<label for="dfCategory">' . __('Category', 'wp-latest-posts') . '</label>' . '<br/>';

/**
 * display text field
 */
echo '<input type="hidden" name="wplp_dfText" value="">';
echo '<input id="dfText"' . $textChecked . ' type="checkbox" name="wplp_dfText" value="' .
    htmlspecialchars("Text") . '">';
echo '<label for="dfText">' . __('Text', 'wp-latest-posts') . '</label>' . '<br/>';

/**
 * display read more field
 */
echo '<input type="hidden" name="wplp_dfReadMore" value="">';
echo '<input id="dfReadMore"' . $readMoreChecked . ' type="checkbox" name="wplp_dfReadMore" value="' .
    htmlspecialchars("Read more") . '">';
echo '<label for="dfReadMore">' . __('Read more', 'wp-latest-posts') . '</label>' . '<br/>';

echo '</ul></div>';    //arrow_col
echo '</div>';
echo '</div>';
