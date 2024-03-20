export function EserpModule() {
    // Expanding/Collapsing of FAQ sections on eSERP
    $(document).on('click','.eserp-faq .question-row', function(){
        $(this).parent().toggleClass('expanded');
    });
}
