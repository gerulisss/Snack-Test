(function ($) {
    console.log("front");
    //for qty buttons
    if (!String.prototype.getDecimals) {
        String.prototype.getDecimals = function () {
            var num = this,
                match = ("" + num).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
            if (!match) {
                return 0;
            }
            return Math.max(
                0,
                (match[1] ? match[1].length : 0) - (match[2] ? +match[2] : 0)
            );
        };
    }

    $(".component-buttons").on("click", ".plus, .minus", function () {
        var $qty = $(this).closest(".quantity").find(".component-qty"),
            currentVal = parseFloat($qty.val()),
            max = parseFloat($qty.attr("max")),
            min = parseFloat($qty.attr("min")),
            step = $qty.attr("step");

        // Format values
        if (!currentVal || currentVal === "" || currentVal === "NaN")
            currentVal = 0;
        if (max === "" || max === "NaN") max = "";
        if (min === "" || min === "NaN") min = 0;
        if (
            step === "any" ||
            step === "" ||
            step === undefined ||
            parseFloat(step) === "NaN"
        )
            step = 1;

        // Change the value
        if ($(this).is(".plus")) {
            if (max && currentVal >= max) {
                $qty.val(max);
            } else {
                $qty.val((currentVal + parseFloat(step)).toFixed(step.getDecimals()));
            }
            if ((currentVal + parseFloat(step)).toFixed(step.getDecimals()) >= 1) {
                $qty.addClass("is-active");
                $(this).siblings(".minus").css("display", "block");
            }
        } else {
            if (min && currentVal <= min) {
                $qty.val(min);
            } else if (currentVal > 0) {
                $qty.val((currentVal - parseFloat(step)).toFixed(step.getDecimals()));
            }
            if ((currentVal - parseFloat(step)).toFixed(step.getDecimals()) < 1) {
                $qty.removeClass("is-active");
                $(this).hide();
            }
        }
        $qty.trigger("change");
    });

    //ripple effect
    $(".component-buttons").on("mousedown", ".plus, .minus", function (e) {
        var $self = $(this);
        if ($self.is(".btn-disabled")) {
            return;
        }

        if ($self.closest(".plus, .minus")) {
            e.stopPropagation();
        }
        var initPos = $self.css("position"),
            offs = $self.offset(),
            x = e.pageX - offs.left,
            y = e.pageY - offs.top,
            dia = Math.min(this.offsetHeight, this.offsetWidth, 100),
            $ripple = $("<div/>", {
                class: "ripple",
                appendTo: $self,
            });
        if (!initPos || initPos === "static") {
            $self.css({position: "relative"});
        }
        $("<div/>", {
            class: "rippleWave",
            css: {
                background: $self.data("ripple"),
                width: dia,
                height: dia,
                left: x - dia / 2,
                top: y - dia / 2,
            },
            appendTo: $ripple,
            one: {
                animationend: function () {
                    $ripple.remove();
                },
            },
        });
    });
    if ($(".snack_components_wrapper").length) {
        //components tabs
        $(".snack-components-nav").on("click", "a", function (e) {
            e.preventDefault();
            $(".snack-components-tab").each(function () {
                $(this).removeClass("fade-in");

                $(this)
                    .parents(".slimScrollDiv")

                    .height(0);
            });
            $(".snack-components-nav a").each(function () {
                $(this).removeClass("active");
            });
            $(this).addClass("active");

            $(`${e.target.hash}`).addClass("fade-in");
            if ($(`${e.target.hash}`).height() > 450) {
                if (!$(`${e.target.hash}`).parents(".slimScrollDiv").length) {
                    if (window.matchMedia("(min-width: 990px)").matches) {
                        $(`${e.target.hash}`).css("padding-right", "25px");
                        $(`${e.target.hash}`).slimScroll({
                            height: 450,
                            railVisible: true,
                            alwaysVisible: true,
                            size: "6px",
                            color: "#FF0329",
                            railColor: "#EAEAEA",
                            railOpacity: 1,
                            wheelStep: 5,
                        });
                    }
                }
            }
            $(`${e.target.hash}`)
                .parents(".slimScrollDiv")

                .height(450);
        });
        ///slimscroll on Tabs
        $(".snack-components-tab").each(function () {
            if (window.matchMedia("(min-width: 990px)").matches) {
                if ($(this).height() > 450) {
                    $(this).css("padding-right", "25px");
                    $(this).slimScroll({
                        height: 450,
                        railVisible: true,
                        alwaysVisible: true,
                        size: "6px",
                        color: "#FF0329",
                        railColor: "#EAEAEA",
                        railOpacity: 1,
                        wheelStep: 5,
                    });
                }
            } else {
                $(this).slick({
                    // centerMode: true,
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    arrows: false,
                    infinite: false,
                    responsive: [
                        {
                            breakpoint: 767,
                            settings: {
                                slidesToShow: 4,
                            },
                        },
                        {
                            breakpoint: 500,
                            settings: {
                                slidesToShow: 3,
                            },
                        },
                        {
                            breakpoint: 380,
                            settings: {
                                slidesToShow: 2,
                            },
                        },
                    ],
                });
            }
        });

        tippy("[data-tippy-content]");
    }
    //Formatting price string

    let symbol = SNACK_FRONT_DATA.wc_symbol,
        pricePosition = SNACK_FRONT_DATA.price_position,
        wcDecimals = SNACK_FRONT_DATA.decimals || 2;
    const u_wc_price = (price) => {
        //recreating wc_price() function
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
    };
    const u_wc_price_sale = (price, regular_price) => {
        switch (pricePosition) {
            case "left":
                return `<del>${symbol}${regular_price.toFixed(
                    wcDecimals
                )}</del><ins>${symbol}${price.toFixed(wcDecimals)}</ins>`;
            case "right":
                return `<del>${regular_price.toFixed(
                    wcDecimals
                )}${symbol}</del><ins>${price.toFixed(wcDecimals)}${symbol}</ins>`;
            case "left_space":
                return `<del>${symbol} ${regular_price.toFixed(
                    wcDecimals
                )}</del><ins>${symbol} ${price.toFixed(wcDecimals)}</ins>`;
            case "right_space":
                return `<del>${regular_price.toFixed(
                    wcDecimals
                )} ${symbol}</del><ins>${price.toFixed(wcDecimals)} ${symbol}</ins>`;
        }
    };
    window.u_wc_price = u_wc_price;
    window.u_wc_price_sale = u_wc_price_sale;
    // Dish tabs
    if ($(".snack-component-tabs-wrapper").length) {
        $(".snack-tab-link").on("click", function (e) {
            e.preventDefault();
            let tabId = $(this).attr("data-tab-id");
            $(".component-item-tab").each(function () {
                $(this).removeClass("fade-in");
            });
            $(".snack-tab-link").each(function () {
                $(this).removeClass("active");
            });
            $(this).addClass("active");
            $(`#${tabId}`).addClass("fade-in");
        });
    }
    // Handle fancybox for cart meta
    $(document.body).on("click", ".snack-composition-toggle", function () {
        $.fancybox.open({
            src: `#u-pizza-${$(this).attr("data-product-id")}`,
            type: "inline",
            touch: false,
        });
    });
})(jQuery);
