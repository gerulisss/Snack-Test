(function ($) {
    function calculateSnackSimple() {
        const dataComponents = JSON.parse(
            $('.snack_components_wrapper').attr('data-snack')
        );
        console.log(dataComponents.snack.extra);
        let initialPrice = $(".snack_components_wrapper").attr("data-price");

        if ($("form.variations_form").length === 0 && $("form.cart").length > 0) {
            $(".component-buttons").on("click", ".plus, .minus", function () {
                calculate();
            });
            // Calculation process
            const calculate = () => {
                let sums = parseFloat(initialPrice);
                $("#add-component .snack-components-item").each(function () {
                    let val = $(this).find(".component-qty").val();
                    let componentId = $(this)
                        .find(".component-buttons")
                        .attr("data-food-item");
                        let componentObject = Object.values(dataComponents.snack.extra).find(
                            (component) => component.id === componentId
                        );
                        if (componentObject !== undefined) {
                            sums += parseFloat(componentObject.price) * parseInt(val);
                        }
                });
                console.log(sums);
                refreshPriceHtml(sums);
            };
            // Refresh prices
            const refreshPriceHtml = (sums) => {
                let priceContainer = $(".product").find(".price");
                priceContainer.html(sums);
            }
        }
    }
    if ($(".snack_components_wrapper").length > 0) {
        calculateSnackSimple();
    }
})(jQuery);