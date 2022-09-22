(function($) {
    // console.log("settings");
    const uploadGroupImage = (el) => {

            // Create a new media frame
         let frame = wp.media({
                multiple: false  // Set to true to allow multiple files to be selected
            });


            // When an image is selected in the media frame...
            frame.on( 'select', function() {

                // Get media attachment details from the frame state
                const attachment = frame.state().get('selection').first().toJSON();
                console.log(attachment);
                el.find("input[name$= 'image]']").val(
                    attachment.sizes.hasOwnProperty("thumbnail")
                  ? attachment.sizes.thumbnail.url
                  : attachment.sizes.full.url
                );
                el.find("input[name$='imageId]']").val(attachment.id);
                el.find("img").attr("src", attachment.sizes.full.url);
            });

            // Finally, open the modal on click
            frame.open();
    };
    const uploadComponentImage = (el) => {

        // Create a new media frame
        let frame = wp.media({
            multiple: false  // Set to true to allow multiple files to be selected
        });


        // When an image is selected in the media frame...
        frame.on( 'select', function() {

            // Get media attachment details from the frame state
            const attachment = frame.state().get('selection').first().toJSON();
            console.log(attachment);
            el.find("input[name$= 'image]']").val(
                attachment.sizes.hasOwnProperty("thumbnail")
                    ? attachment.sizes.thumbnail.url
                    : attachment.sizes.full.url
            );
            el.find("input[name$='imageId]']").val(attachment.id);
            el.find("img").attr("src", attachment.sizes.full.url);
        });

        // Finally, open the modal on click
        frame.open();
    };
    $('#snack-test-settings')
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
        .on('click', '.group-image', function () {
            uploadGroupImage($(this));
        })
        .on('click', '.component-image', function () {
            uploadComponentImage($(this));
        })
        .on('click', '.add-group', function () {
            // console.log(SNACK_TEST_DATA.url);
            let index = $(".wc-metabox").length + 1;
            const template = wp.template( 'snack-group' );
            const dataGroup = {
                index: index,
                id: {
                    name: `snack_data[${index}][id]`
                },
                name: {
                    name: `snack_data[${index}][group_name]`
                },
                image: {
                    name: `snack_data[${index}][image]`,
                    value: SNACK_TEST_DATA.url + "images/placeholder.svg",
                },
                imageId: {
                    name: `snack_data[${index}][imageId]`
                },
            };
            $(".wc-metaboxes").append(template(dataGroup));
        })
        .on('click', '.remove-group', function () {
            const answer = confirm("Do you want to remove group?");
            if (answer) {
                let groupContainer = $(this).closest(".wc-metabox");

                $(groupContainer).remove();
            }
            $(".wc-metabox").each(function (index, el) {
                let _index = index + 1;

                $(this)
                    .find("input, textarea")
                    .prop("name", function (i, val) {
                        let fieldName = val.replace(
                            /pizza_data[[0-9] +\]/g,
                            "pizza_data[" + _index + "]"
                        );
                        return fieldName;
                    });
                $(this).find('h3 > input[name$="[id]"]').val(_index);
                $(this).closest(".wc-metabox").attr("data-index", _index);
            });
        })
        .on('click', '.add-component', function () {
            let indexGroup = $(this).closest(".wc-metabox").attr("data-index");
            // let indexComponent = $(".group-component").length +1;
            let indexComponent =
                Math.max.apply(
                null,
                $("#snack-test-settings")
                    .find(".remove-component")
                    .map(function () {
                        return $(this).attr("data-id");
                    })
                    .get()
            ) + 1;
            console.log(indexComponent);
            // let indexComponent = $(this).closest(".wc-metabox").find(".group-component").length +1;
            const template = wp.template( 'snack-component' );
            const dataComponent = {
                index: indexComponent,
                id: {
                    name: `snack_data[${indexGroup}][components][${indexComponent}][id]`,
                },
                name: {
                    name: `snack_data[${indexGroup}][components][${indexComponent}][name]`,
                    value: "New component",
                },
                price: {
                    name: `snack_data[${indexGroup}][components][${indexComponent}][price]`,
                    value: 0,
                },
                weight: {
                    name: `snack_data[${indexGroup}][components][${indexComponent}][weight]`,
                },
                description: {
                    name: `snack_data[${indexGroup}][components][${indexComponent}][description]`,
                },
                image: {
                    name: `snack_data[${indexGroup}][components][${indexComponent}][image]`,
                    value: SNACK_TEST_DATA.url + "images/placeholder.svg",
                },
                imageId: {
                    name: `snack_data[${indexGroup}][components][${indexComponent}][imageId]`,
                },
                meta: {
                    name: `snack_data[${indexGroup}][components][${indexComponent}][meta]`,
                },
            };
            $(this)
                .parent()
                .siblings(".group-components")
                .append(template(dataComponent));
            // $(".wc-metaboxes").append(template(dataComponent));
        })
        .on("click", ".remove-component", function () {
            const answer = confirm("Do you want to remove component?");
            if (answer) {
                let componentContainer = $(this).closest(".group-component");

                $(componentContainer).remove();
            }
            // $(".group-component").each(function (index, el) {
            //     let _index = index + 1;
            //
            //     $(this)
            //         .find("input, textarea")
            //         .prop("name", function (i, val) {
            //             let fieldName = val.replace(
            //                 /\[components][[0-9] +\]/g,
            //                 "[components][" + _index + "]"
            //             );
            //             return fieldName;
            //         });
            //     $(this).find('h3 > input[name$="[id]"]').val(_index);
            //     $(this).find(".remove-component").attr("data-id", _index);
            // });
        });
    })(jQuery);