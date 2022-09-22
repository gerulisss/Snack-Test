(function ($) {
    const snackComponents = SNACK_PRODUCT_DATA.snack_components;
    let symbol = SNACK_PRODUCT_DATA.wc_symbol,
        pricePosition = SNACK_PRODUCT_DATA.price_position,
        wcDecimals = SNACK_PRODUCT_DATA.decimals || 2;
    const snack_wc_price = (price) => {
        // wc_price() function
        switch (pricePosition) {
            case "left":
                return `${symbol}${price.toFixed(wcDecimals)}`;
            case "right":
                return `${price.toFixed(wcDecimals)}${symbol}`;
            case "left_space":
                return `${symbol} ${price.toFixed(wcDecimals)}`;
            case "right_space":
                return `${price.toFixed(wcDecimals)} ${symbol}`;
        }
    }
    console.log(snackComponents);
    $("#snack_base_components").selectWoo({
        placeholder: "Consists of components",
    })
        .on('select2:select', function(e) {
            let data = e.params.data;
            console.log(data);
            let foundComponent = snackComponents.find(
                (component) => component.id == data.id
            );
            if(!foundComponent) {
                return;
            }
            console.log(foundComponent);
            const template = wp.template("snack-component");
            const dataComponent = {
                index: data.id,
                id: {
                    name: `snack_base[${data.id}][id]`,
                    value: data.id,
                },
                name: {
                    name: `snack_base[${data.id}][name]`,
                    value: foundComponent.name,
                },
                price: {
                    name: `snack_base[${data.id}][price]`,
                    value: snack_wc_price(parseFloat(foundComponent.price)),
                    raw: foundComponent.price,
                },
                weight: {
                    name: `snack_base[${data.id}][weight]`,
                    value: foundComponent.weight,
                },

                image: {
                    name: `snack_base[${data.id}][image]`,
                    value: foundComponent.image,
                },
                visible: {
                    name: `snack_base[${data.id}][visible]`,
                    value: foundComponent.visible,
                },
                required: {
                    name: `snack_base[${data.id}][required]`,
                    value: foundComponent.required,
                },
            };
            $('#snack_consists_block').append(template(dataComponent));
        })
        .on("select2:unselect", function (e) {
            let data = e.params.data;
            $(`#snack_consists_block .group-component[data-id=${data.id}]`).remove();
        });
    $("#snack_extra_components").selectWoo({
        placeholder: "Extra components",
    });
    $("#dish_components").selectWoo({
        placeholder: "Extra components",
    });
    $('#snack_block_1')
        .on('click', '.edit-component', function() {
            if ($(this).is(".active")) {
                $(this)
                    .closest(".group-component")
                    .find(".component-body-collapse")
                    .slideUp();
                $(this).closest(".group-component").find(".component-body").show();
                $(this).removeClass("active");
            } else {
                $(this)
                    .closest(".group-component")
                    .find(".component-body-collapse")
                    .slideDown();
                $(this).closest(".group-component").find(".component-body").hide();
                $(this).addClass("active");
            }
        })

        // Handle tab select
        $("input[name=snack_type]").on("change", function () {
            let value = $(this).val();
            $(`#snack_block_1`).hide();
            $(`#snack_block_2`).hide();
            $(`#snack_block_${value}`).show();
        });

        if ($("input[name=snack_type]:checked").val() == 1) {
            $(`#snack_block_2`).hide();
        } else {
            $(`#snack_block_1`).hide();
        }
        //toggle sides components checkbox
        $("#snack_sides").on("change", function () {
            if ($(this).is(":checked")) {
                $("#snack_sides_block").show();
            } else {
                $("#snack_sides_block").hide();
            }
        });
        //toggle floors components checkbox
        $("#snack_floors").on("change", function () {
            if ($(this).is(":checked")) {
                $("#snack_floors_block").show();
            } else {
                $("#snack_floors_block").hide();
            }
        });
})(jQuery);