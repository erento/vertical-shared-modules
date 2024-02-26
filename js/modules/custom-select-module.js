export function CustomSelectPopup(
    subscribeToResize
) {
    var brandSelect = $('.search-box select#brand');
    var modelSelect = $('.search-box select#model');
    var allOption = $('#clone-option-all');
    var disabledOption = $('#clone-option-disabled');
    var models;
    var isCustomSearchboxDropdownOpen = false;
    var isAtLeastDesktop = false;

    subscribeToResize(function(event, data) {
        isAtLeastDesktop = data.isAtLeastDesktop;
        closeCustomSelectPopup();
    });

    function closeCustomSelectPopup() {
        if (isCustomSearchboxDropdownOpen) {
            $('.custom-select-wrapper').remove();
            $('.custom-select-overlay').remove();
            isCustomSearchboxDropdownOpen = false;
        }
    }

    function setSearchboxModelsSelect() {
        if (brandSelect.prop('selectedIndex') == 0) {
            modelSelect.find('option').remove();
            modelSelect.append(allOption);
            modelSelect.append(disabledOption);
        } else {
            models = brandSelect.find(':selected').attr('data-models');
            models = JSON.parse(models);

            modelSelect.find('option').remove();
            if (models != false) {
                models.forEach(model => {
                    modelSelect.append('<option value="' + model.slug + '">' + model.name + '</option>');
                });
            } else {
                modelSelect.append(allOption);
            }
        }
    }

    function setSearchboxSelectOption(element) {
        var index = element.getAttribute('data-index');
        var select_parent = $(element).parent().parent().parent().find('select');

        select_parent.find('option').prop('selected', false);
        select_parent.find('option').eq(index).prop('selected', true);

        if (select_parent.is('#brand')) {
            setSearchboxModelsSelect();
        }
    }

    $('.search-box select').on('mousedown', function (e) {
        if (isAtLeastDesktop) {
            e.preventDefault();
            closeCustomSelectPopup();

            $('search-box-component').append('<div class="custom-select-overlay"></div>');

            var custom_select_html = '<div class="custom-select-wrapper">';
            custom_select_html += '<div class="custom-select">';
            $(this).find('option').each(function (index, element) {
                custom_select_html += '<div class="custom-select-option';
                if ($(element).prop('selected')) custom_select_html += ' selected';
                if ($(element).prop('disabled')) custom_select_html += ' disabled';
                custom_select_html += '" data-index="' + index + '">';
                custom_select_html += element.text;
                custom_select_html += '</div>';
            });
            custom_select_html += '</div>';
            $(this).closest('.input-container').append(custom_select_html);

            isCustomSearchboxDropdownOpen = true;
        }
    });

    $(document).on('click', '.custom-select-overlay', function () {
        closeCustomSelectPopup();
    });

    $(document).on('click', '.custom-select-option', function (event) {
        setSearchboxSelectOption(event.target);
        closeCustomSelectPopup();
    });

    brandSelect.on('change', function () {
        setSearchboxModelsSelect();
    });

    return {
        closeCustomSelectPopup
    }
}
