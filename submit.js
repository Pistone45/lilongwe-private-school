function SubmitFormData() {
    var mark = $("#mark").val();
    var assignments_id = $("#assignments_id").val();
    var students_student_no = $("#students_student_no").val();
    $.post("submit.php", { mark: mark, assignments_id: assignments_id,
     students_student_no: students_student_no },
    function(data) {
	 $('#results').html(data);
	 $('#myForm')[0].reset();
    });
}