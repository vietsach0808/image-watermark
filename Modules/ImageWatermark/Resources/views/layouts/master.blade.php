<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Module ImageWatermark</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>
    {{-- Laravel Mix - CSS File --}}
    {{-- <link rel="stylesheet" href="{{ mix('css/imagewatermark.css') }}"> --}}
    <script>

        $(document).ready(function () {
            $("#image").change(function (event) {
                var previewImg = $('.preview-img');
                var reader = new FileReader();

                reader.onload = function (e) {
                    var urll = e.target.result;
                    $(previewImg).attr("src", urll);
                    previewImg.parent().css("background", "transparent");
                    previewImg.show();
                    previewImg.siblings("p").hide();
                };
                reader.readAsDataURL(this.files[0]);
                setTimeout(function () {
                    $('.image-box').css('height', parseInt($('.preview-img').height()) + 'px');
                }, 100);
                $('.iw-preview').show();

            });
            setTimeout(function () {
                $('.image-box').css('height', parseInt($('.preview-img').height()) + 'px');
            }, 100);

            $(document).on('change', '.font_size', function () {
                $('.preview-text').css('font-size', $(this).val()+'px');
            });

            $(document).on('keyup', '.horizontal', function () {
                $('.preview-text').css('left', $(this).val()+'px');
            });

            $(document).on('keyup', '.vertical', function () {
                $('.preview-text').css('top', $(this).val()+'px');
            });

            $(document).on('change', '.horizontal', function () {
                $('.preview-text').css('left', $(this).val()+'px');
            });

            $(document).on('change', '.vertical', function () {
                $('.preview-text').css('top', $(this).val()+'px');
            });

            $(document).on('keyup', '.input-preview-text', function () {
                $('.preview-text').text($(this).val());
            });


            $('.add_new_text').click(function () {
                $(document).find('.row-content-image').removeClass('active');
                $('.list-image-content').append(addNewText());
                let current_row_content = $(document).find('.row-content-image.active');
                $('.preview-text').css('font-size', current_row_content.find('.font_size').val()+'px');
                $('.preview-text').text(current_row_content.find('.input-preview-text').val());
                let x = current_row_content.find('.horizontal').val() + 'px';
                let y = current_row_content.find('.vertical').val() + 'px';
                $('.preview-text').css({"top": y, "left": x});
            });

            $(document).on('click', '.delete-content:not(.disabled)', function () {
                let check_active = $(this).parents('.row-content-image.active');
                $(this).parents('.row-content-image').remove();
                if(check_active.length) {
                    $(document).find('.row-content-image:first-child').addClass('active');
                }
            });

            $(document).on('click', '.preview-content', function () {
                $(document).find('.row-content-image').removeClass('active');
                let current_row_content = $(this).parents('.row-content-image');
                current_row_content.addClass('active');
                $('.preview-text').css('font-size', current_row_content.find('.font_size').val()+'px');
                $('.preview-text').text(current_row_content.find('.input-preview-text').val());
                let x = current_row_content.find('.horizontal').val() + 'px';
                let y = current_row_content.find('.vertical').val() + 'px';
                $('.preview-text').css({"top": y, "left": x});
            });
        });

        function addNewText() {
            return `
            <div class="form-group row row-content-image active">
                <div class="col-lg-3">
                    <div>
                        <button type="button" class="btn btn-secondary preview-content"> @lang('imagewatermark::iw.preview_button')</button>
                        <button type="button" class="btn btn-danger delete-content"> @lang('imagewatermark::iw.delete')</button>
                    </div>
                </div>
                <div class="col-lg-2">
                    <select class="form-control font_size" name="font_size[]">
                        @php
                        $dataFontSize = old('font_size', isset($iwImage) ? $iwImage->font_size : 14);
                        @endphp
                        @foreach($listFontSize as $fontSize)
                            <option value="{{$fontSize}}" @if($dataFontSize == $fontSize) selected @endif>{{$fontSize}}px</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3">
                    <input class="form-control input-preview-text" value="0123456789">
                </div>
                    <div class="col-lg-2">
                    <input type="text" class="form-control horizontal" name="horizontal[]" value="0">
                </div>
                    <div class="col-lg-2">
                    <input type="text" class="form-control vertical" name="vertical[]" value="0">
                    <input type="hidden" class="form-control" name="background[]" value="0">
                    <input type="hidden" class="form-control" name="image_content_id[]" value="0">
                </div>
            </div>
            `;
        }

        function deleteImage(form_id) {
            if(confirm("@lang('imagewatermark::iw.confirm_delete')")) {
                $(form_id).submit();
            }
        }

    </script>


    <script type="text/javascript">
        $(document).ready(function () {
            var elmids = ['preview-img'];

            var x, y = 0;       // variables that will contain the coordinates

            // Get X and Y position of the elm (from: vishalsays.wordpress.com)
            function getXYpos(elm) {
                x = elm.offsetLeft;        // set x to elm’s offsetLeft
                y = elm.offsetTop;         // set y to elm’s offsetTop

                elm = elm.offsetParent;    // set elm to its offsetParent

                //use while loop to check if elm is null
                // if not then add current elm’s offsetLeft to x
                //offsetTop to y and set elm to its offsetParent
                while (elm != null) {
                    x = parseInt(x) + parseInt(elm.offsetLeft);
                    y = parseInt(y) + parseInt(elm.offsetTop);
                    elm = elm.offsetParent;
                }

                // returns an object with "xp" (Left), "=yp" (Top) position
                return {'xp': x, 'yp': y};
            }

            function preview(x, y) {
                console.log(x + ' , ' + y);
                $('.preview-text').css({"top": y, "left": x});
                $(document).find('.row-content-image.active').find('.horizontal').val(x);
                $(document).find('.row-content-image.active').find('.vertical').val(y);
            }

            // Get X, Y coords, and displays Mouse coordinates
            function getCoords(e) {
                var xy_pos = getXYpos(this);

                // if IE
                if (navigator.appVersion.indexOf("MSIE") != -1) {
                    // in IE scrolling page affects mouse coordinates into an element
                    // This gets the page element that will be used to add scrolling value to correct mouse coords
                    var standardBody = (document.compatMode == 'CSS1Compat') ? document.documentElement : document.body;

                    x = event.clientX + standardBody.scrollLeft;
                    y = event.clientY + standardBody.scrollTop;
                } else {
                    x = e.pageX;
                    y = e.pageY;
                }

                x = x - xy_pos['xp'];
                y = y - xy_pos['yp'];

                // displays x and y coords in the #coords element
                // console.log('X= '+ x+ ' ,Y= ' +y);
            }

            // register onmousemove, and onclick the each element with ID stored in elmids
            for (var i = 0; i < elmids.length; i++) {
                if (document.getElementById(elmids[i])) {
                    // calls the getCoords() function when mousemove
                    document.getElementById(elmids[i]).onmousemove = getCoords;

                    // execute a function when click
                    document.getElementById(elmids[i]).onclick = function () {
                        preview(x, y);
                    };
                }
            }
        });
    </script>
    <style>
        .preview-img {
            max-width: 900px;
            position: absolute;
        }

        .iw-container {
            background: #f7f7f7;
            padding: 20px;
            border-radius: 3px;
        }

        .preview-img {
            cursor: cell;
        }

        .image-box {
            position: relative;
        }

        .preview-text {
            position: absolute;
            color: #fff;
        }
        .disabled {
            cursor: not-allowed;
        }
    </style>
</head>
<body>
@yield('content')

{{-- Laravel Mix - JS File --}}
{{-- <script src="{{ mix('js/imagewatermark.js') }}"></script> --}}
</body>
</html>
