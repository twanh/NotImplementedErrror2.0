/**
 * Hides the count part of the side tables
 */
function hidePartColumn() {
    $("[id$='-count']").hide();
}

$(function() {
    hidePartColumn();
});
