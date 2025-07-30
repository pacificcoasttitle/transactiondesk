$(document).ready(function () {
    $("#title_officer_list").on("change", function () {
        var title_officer_survey_id = $(this).val();
        var title_officer_survey_name = $(this).find("option:selected").text();
        if (title_officer_survey_id) {
            $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
            $('#page-preloader').css('display', 'block');
            $.ajax({
                url: base_url + "get-survey-details",
                type: "post",
                data: {
                    title_officer_survey_id: title_officer_survey_id,
                    title_officer_survey_name: title_officer_survey_name
                },
                dataType: "html",
                success: function (response) {

                    var results = JSON.parse(response);
                    console.log(results);
                    if (results.status == 'success') {

                        $('.survey-cards').html(results.survey.survey_cards);
                        $('.survey-table').html(results.survey.survey_rating_details);
                    }
                    else if (results.status == 'error') {
                        alert(results.msg);
                    }
                    $('#page-preloader').css('display', 'none');
                }
            });
        }
    });

    $('#title_officer_list').removeClass('selectpicker').selectpicker('destroy');
});

function displayComment(comments) {
    if (comments.length > 0) {
        $("#commentList").html(comments);
        $("#commentModal").modal("show");
    }
}
