define([
    'jquery'    
], function($) {
    'use strict';
    var searchControlElement = $('#search_mini_form .field.search .control').first();
    searchControlElement.append('<div id="searchAutocomplete"><div class="searchCategory"><ul></ul></div><div class = "listProduct"></div><button type="submit">View All Product</button></div>');
    $('#search_autocomplete').remove();
    $('#search').unbind();
    $(window).click(function() {
        $('#searchAutocomplete').hide();
    });
    $('.block.block-search').click(function(event) {
        event.stopPropagation();
    });

    var numberofrow = $("input[name=numberOfrow]").val();
    var number = $("input[name=numberProduct]").val();
    var widthlist = numberofrow*120;
    var widthrow = widthlist+140;

    $('#search').bind('input', function() {
        var searchInput = $(this);
        var searchInputValue = searchInput.val().replace("/^[a-zA-Z0-9 _-]+$/i", "");
        if (searchInputValue.length >= 3) {
            url = "<?php echo $this->getAjaxUrl(); ?>";
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    query: searchInputValue,
                    category: $('#searchCategories select').first().val()
                },
                success: function(data) {
                    console.log(data);
                    var searchResultHtml = '';
                    var size = Object.keys(data)[1].length;
                    console.log(size);

                    var i = 1;
                    var r;
                    if (Number.isInteger(size / numberofrow)) {
                        r = parseInt(size / numberofrow);
                    } else {
                        r = parseInt(size / numberofrow) + 1;
                    }
                    for (let k = 1; k <= r; k++) {
                        searchResultHtml += '<ul>';
                        for (let j = 1; j <= numberofrow; j++) {
                            searchResultHtml += '<li data-url="' + data[1][i]['url'] + '">';
                            searchResultHtml += '<div class="sa-image"><img src="' + data[1][i]['thumbnail'] + '"></div>';
                            searchResultHtml += '<div class="sa-prop">';
                            searchResultHtml += '<div class="sa-title"><p>' + data[1][i]['name'] + '</p></div>';
                            if (data[i]['rating']) {
                                searchResultHtml += '<div class="sa-rating"><div class="rating-summary"><div class="rating-result" title="' + data[1][i]['rating'] + '%"><span style="width:' + data[1][i]['rating'] + '%"><span>' + data[1][i]['rating'] + '%</span></span></div></div></div>';
                            }
                            searchResultHtml += '<div class="sa-price"><p>' + data[1][i]['price'] + '<p></div>';
                            searchResultHtml += '</div>';
                            searchResultHtml += '</li>';

                            i++;
                            if (i > size) {
                                break;
                            }
                        }
                        searchResultHtml += '</ul>';
                        $('#searchAutocomplete .listProduct').first().html(searchResultHtml);

                    }

                    $("#searchAutocomplete .listProduct ul li").on("click", function() {
                        window.location = $(this).data('url');
                    });
                    if (size > 0) {
                        $('#searchAutocomplete').show();
                        $('#searchAutocomplete button').show();
                        $('#searchAutocomplete').css('width', widthrow+'px');
                        $('#searchAutocomplete .listProduct').css('width', widthlist+'px');
                        $('#searchAutocomplete').css('left', -(widthrow-250)/2+15+'px');
                        $('#searchAutocomplete .searchCategory').css('width', '120px');


                    } else {
                        $('#searchAutocomplete .listProduct').html(' No Product to show ');
                        $('#searchAutocomplete').css('width', '250px');
                        $('#searchAutocomplete .listProduct').css('width', '130px');
                        $('#searchAutocomplete .searchCategory').css('width', '0px');
                        $('#searchAutocomplete').css('left', '15px');
                        $('#searchAutocomplete').show();
                        $('#searchAutocomplete button').hide();
                    }
                },
                error: function(data) {
                    console.log(data);
                }
            });
        } else if (searchInputValue.length != 0) {
            $('#searchAutocomplete .listProduct').html(' Min 3 letters');
            $('#searchAutocomplete').css('width', '250px');
            $('#searchAutocomplete').css('left', '15px');
            $('#searchAutocomplete .listProduct').css('width', '85px');
            $('#searchAutocomplete .searchCategory').css('width', '0px');
            $('#searchAutocomplete').show();
            $('#searchAutocomplete button').hide();

        } else {
            $('#searchAutocomplete').hide();
        }
    });
    $("#search").on("focus", function() {
        var searchInput = $(this);
        var searchInputValue = searchInput.val().replace("/^[a-zA-Z0-9 _-]+$/i", "");
        if (searchInputValue.length >= 3) {
            url = "<?php echo $this->getAjaxUrl(); ?>";
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    query: searchInputValue,
                    category: $('#searchCategories select').first().val()
                },
                success: function(data) {
                    console.log(data);
                    var searchResultHtml = '';
                    var size = Object.keys(data)[1].length;
                    console.log(size);
                    var i = 1;
                    var r;
                    if (Number.isInteger(size / numberofrow)) {
                        r = parseInt(size / numberofrow);
                    } else {
                        r = parseInt(size / numberofrow) + 1;
                    }
                    for (let k = 1; k <= r; k++) {
                        searchResultHtml += '<ul>';
                        for (let j = 1; j <= numberofrow; j++) {
                            searchResultHtml += '<li data-url="' + data[1][i]['url'] + '">';
                            searchResultHtml += '<div class="sa-image"><img src="' + data[1][i]['thumbnail'] + '"></div>';
                            searchResultHtml += '<div class="sa-prop">';
                            searchResultHtml += '<div class="sa-title"><p>' + data[1][i]['name'] + '</p></div>';
                            if (data[i]['rating']) {
                                searchResultHtml += '<div class="sa-rating"><div class="rating-summary"><div class="rating-result" title="' + data[1][i]['rating'] + '%"><span style="width:' + data[1][i]['rating'] + '%"><span>' + data[1][i]['rating'] + '%</span></span></div></div></div>';
                            }
                            searchResultHtml += '<div class="sa-price"><p>' + data[1][i]['price'] + '<p></div>';
                            searchResultHtml += '</div>';
                            searchResultHtml += '</li>';

                            i++;
                            if (i > size) {
                                break;
                            }
                        }
                        searchResultHtml += '</ul>';
                        $('#searchAutocomplete .listProduct').first().html(searchResultHtml);

                    }

                    if (size > 0) {
                        $('#searchAutocomplete').show();
                        $('#searchAutocomplete button').show();
                        $('#searchAutocomplete').css('width', widthrow+'px');
                        $('#searchAutocomplete .listProduct').css('width', widthlist+'px');
                        $('#searchAutocomplete').css('left', -(widthrow-250)/2+15+'px');
                        $('#searchAutocomplete .searchCategory').css('width', '120px');


                    } else {
                        $('#searchAutocomplete .listProduct').html('No Product to show ');
                        $('#searchAutocomplete').css('width', '250px');
                        $('#searchAutocomplete').css('left', '15px');
                        $('#searchAutocomplete .listProduct').css('width', '130px');
                        $('#searchAutocomplete .searchCategory').css('width', '0px');
                        $('#searchAutocomplete').show();
                        $('#searchAutocomplete button').hide();
                    }
                    $("#searchAutocomplete .listProduct ul li").on("click", function() {
                        window.location = $(this).data('url');
                    });
                },
                error: function(data) {
                    console.log(data);
                }
            });
        } else if (searchInputValue.length != 0) {
            $('#searchAutocomplete .listProduct').html(' Min 3 letters ');
            $('#searchAutocomplete').css('width', '250px');
            $('#searchAutocomplete').css('left', '15px');
            $('#searchAutocomplete .listProduct').css('width', '85px');
            $('#searchAutocomplete .searchCategory').css('width', '0px');
            $('#searchAutocomplete').show();
            $('#searchAutocomplete button').hide();

        } else {
            $('#searchAutocomplete').hide();
        }
    });

});