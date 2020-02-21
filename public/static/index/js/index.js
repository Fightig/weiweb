$(function(){
    $(document).on("click",".search_input",function(){
        $(".search_input_search").css("display","block");
        $(this).css("display","none");
        $(".member").html('');
        $('.lookAll').html('');
    });
    $(document).on("click",".cancel",function(){
        $(".search_input_search").css("display","none");
        $(".search_input").css("display","block");
        // 请求第一页数据加入 .mamber里
        $.ajax({
            url: '/list',
            type: 'post',
            data: {'pageNumber':1,'searchText':''},
            success: function (res) {
                console.log(res.data.length);
                if (res.data.length !== 0) {
                    var myHtml = '';
                    for (var i = 0; i < res.data.length; i++) {
                        myHtml += `<li class="member-item">
                                    <a href="/detail/` + res.data[i]['id'] + `">
                                        <div class="avatar">
                                            <img src="` + res.data[i]['head_img'] + `" alt="">
                                        </div>
                                    </a>
                                    <span>` + res.data[i]['nickname'] + `</span>
                                </li>`;
                    }
                    $('.member').html(myHtml);
                    $('.lookAll').html('加载更多');
                }else {
                    $('.lookAll').html('暂无数据');
                }
            }
        });
    });

    $('#search').on("input propertychange",function () {
        // 请求接口
        $.ajax({
            url: '/list',
            type: 'post',
            data: {'pageNumber': 1,'searchText': $(this).val()},
            success:function (res) {
                var myHtml = '';
                if (res.data.length !==  0) {
                    for (var i = 0; i < res.data.length; i++) {
                        myHtml += `<li class="member-item">
                                    <a href="/detail/` + res.data[i]['id'] + `">
                                        <div class="avatar">
                                            <img src="` + res.data[i]['head_img'] + `" alt="">
                                        </div>
                                    </a>
                                    <span>` + res.data[i]['nickname'] + `</span>
                                </li>`;
                    }
                    $('.lookAll').html('加载更多');
                    $('.lookAll').attr('data-field',1);
                }else {
                    $('.lookAll').html('');
                }
                $('.member').html(myHtml);
            },
            fail: function (err) {
                
            }
        })

    })
});

// 请求列表数据
function getListData() {
    var add = $('.lookAll').text();
    if (!add || add !== '加载更多') {
        return false;
    }
    var page = $('.lookAll').attr('data-field');
    page = parseInt(page) + 1;
    var text = $('#search').val();
    $.ajax({
        url: '/list',
        type: 'post',
        data: {'pageNumber': page,'searchText': text},
        success: function (res) {
            console.log(res);
            if (res.data.length !==  0) {
                var myHtml = '';
                for (var i = 0; i < res.data.length; i++) {
                    myHtml += `<li class="member-item">
                                    <a href="/detail/` + res.data[i]['id'] + `">
                                        <div class="avatar">
                                            <img src="` + res.data[i]['head_img'] + `" alt="">
                                        </div>
                                    </a>
                                    <span>` + res.data[i]['nickname'] + `</span>
                                </li>`;
                }
                $('.member').append(myHtml);
                $('.lookAll').html('加载更多');
                $('.lookAll').attr('data-field',page);
            }else {
                $('.lookAll').text('没有更多了~');
            }
        },
        fail: function (err) {
            console.log(err);
        }
    })
}