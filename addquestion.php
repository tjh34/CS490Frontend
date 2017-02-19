<?php include 'header.php';?>

<body>

<style>
ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
}
button .out {
    width: 60px;
}
</style>
    <?php include 'nav-bar.php';?>
    <div class="container">

        <form class="question-form" role="form" name="question_maker" method="post" >
            <h3 align="center" >Question Creation Form</h3>

            <div class="form-group">
                <input type="text" name="question_title" class="form-control flat" placeholder="Enter Question Title...">
            </div>
            
            <div class="form-group">
            <textarea class="form-control flat" name="question_body" rows="3" placeholder="Enter Question Body..."></textarea>
            </div>
            <div class="form-group">
            <textarea class="form-control flat" name="question_feedback" rows="3" placeholder="Enter Feedback..."></textarea>
            </div>

            <div class="btn-group form-group">
                <div class="dropdown">
                  <button class="btn dropdown-toggle btn-info" data-toggle="dropdown"><span id="lang">Select Language</span><span class="caret"></span></button>
                  <span class="dropdown-arrow"></span>
                  <ul id="language_dropdown" name="subject" class="question_scroll dropdown-menu">
                  </u>
                </div>
            </div>

            <div class="btn-group form-group">
                <div class="dropdown">
                  <button class="btn dropdown-toggle btn-info" data-toggle="dropdown"><span id="subject">Select Subject</span><span class="caret"></span></button>
                  <span class="dropdown-arrow"></span>
                  <ul id="subject_dropdown" name="subject" class="question_scroll dropdown-menu">
                  </u>
                </div>
            </div>

            <div class="btn-group form-group">
				<div class="dropdown">
					<button class="btn dropdown-toggle btn-info" data-toggle="dropdown"><span id="select">Select Question Type</span><span class="caret"></span></button>
					<span class="dropdown-arrow"></span>
                    <ul id="questiontype_dropdown" class="dropdown-menu">
						<li><a href="#" id="action-1" value="multi">Multiple Choice</a></li>
						<li><a href="#" id="action-2" value="true-false">True/False</a></li>
						<li><a href="#" id="action-3" value="fill">Fill In the Blank</a></li>
						<li><a href="#" id="action-4" value="coding">Coding Question</a></li>
					</ul>
				</div>
			</div>
      <div class="row">
          <p>
            <label for="currentvalue">Please Select Question Difficulty:</label>
            <input type="text" id="currentvalue" style="border: 0px none !important; font-weight:bold;">
          </p>
      </div>
      <div class="row">
        <div id="slider" class="ui-slider"></div>
      </div>
			<div id="mult">
			</div>

          <button class="btn btn-embossed btn-primary btn-block" type="submit">Submit Question</button>
        </form>
          <div id="flash" align="center" style="font-weight: bold;"></div>
</div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- Load JS here for greater good =============================-->
    <script type="text/javascript" src="js/amalgation.min.js"></script>
  
    <script type="text/javascript">
    $.ajax({ 
        type: "GET",
        url: "http://web.njit.edu/~jdl38/application_server/app.php/select_data",
        dataType: "json",
        success: function(d) {
            var languages_dropdown = $('#language_dropdown');
            var subject_dropdown = $('#subject_dropdown');
            d['languages'].forEach(function(e,i,a) {
                languages_dropdown.append('<li><a data-language="' + e.id + '">' + e.name + '</a></li>');
            });
            d['subjects'].forEach(function(e,i,a) {
                subject_dropdown.append('<li><a data-subject="' + e.id + '">' + e.name + '</a></li>');
            });
            $("#questiontype_dropdown li a").click(function(){
              $('#select').text($(this).text());
              $('#select').val($(this).text());
            });
            $("#subject_dropdown li a").click(function(){
              var subject_span = $("#subject");
              subject_span.text($(this).text());
              subject_span.data("subject", $(this).data("subject"));
              console.log(subject_span.data("subject"));
            });
            $("#language_dropdown li a").click(function(){
              var language_span = $("#lang");
              language_span.text($(this).text());
              language_span.data("language", $(this).data("language"));
              console.log(language_span.data("language"));
            });
        },
        error: function(e) {
            console.log(e);
        }
    });
     $(function() {
       $( "#slider" ).slider({
         value:3,
           min: 1,
           max: 5,
           step: 1,
           slide: function( event, ui ) {
             $( "#currentvalue" ).val( ui.value );
           }
       });
       $( "#currentvalue" ).val($( "#slider" ).slider( "value" ) );
     });
    function send_data(answers) {
            var qtype = $('#qtype');
            var request = {};
            request['title'] = $("form input[name='question_title']").val();
            request['spec'] = $("form textarea[name='question_body']").val();
            request['subject'] = $("#subject").data("subject");
            request['language'] = $("#lang").data("language");
            request['feedback'] = $("form textarea[name='question_feedback']").val();
            request['qtype'] = qtype.val();
            request['answers'] = answers;
            request['difficulty'] = $("form input[id='currentvalue']").val();
            console.log(request);
            if (answers.length > 0) {
                $.ajax({
                    type: 'POST',
                    url: 'http://web.njit.edu/~arm32/data_server/app.php/question',
                    contentType: 'application/json; charset=utf-8',
                    dataType: 'json',
                    data: JSON.stringify(request),
                    success: function(data,stat,xhr) {
                        alert(data['message']);
                        window.location = window.location.pathname;
                    },
                    error: function(xhr,stat,err) {
                        console.log("err", err);
                    },
                });
            }else {
                console.warn("Please include the answer(s)");
            }
    }
    function register_multi_hooks() {
        
        $('form').submit(function(e) {
            e.preventDefault();
            var acc = [];    // collect answers
            var obj = {};
            $("#mult input:not([type='hidden'])").each(function(iter) {
                if ($(this).attr("name") === "mult_answer") {
                    obj = {
                        'answer_key' : iter,
                        'answer_value' : this.value,
                        'correct': true
                    };
                } else {
                    obj = {
                        'answer_key' : iter,
                        'answer_value' : this.value,
                        'correct': false
                    };
                }
                
                
                acc.push(obj);
            });
            send_data(acc);   // sending off common form info
        });
    }
    function register_truefalse_hooks() {
        $('form').submit(function(e) {
            e.preventDefault();
            var acc = [];   // collect answers
            var obj = {};
            var group1 = $("#mult input[name='group1']:checked");
            if (group1.length > 0) {
                acc.push({
                    "answer_key":"0",
                    "answer_value":group1.val(),
                    "correct": true
                });
            }
            send_data(acc);   // sending off common form info
        });
    }
    function register_fill_hooks() {
        var match_fill = /[|](.*?)[|]/;   // grab the fill word
        console.log(match_fill);
        $('form').submit(function(e) {
            e.preventDefault();
            var acc = [];    // collect answers
            var obj = {};
            var txt = $("form textarea[name='question_body']");
            console.log(txt);
            var m = txt.val().match(match_fill);
            console.log(m);
            if (m) { 
                txt.val( txt.val().replace(match_fill,"____") ); 
            }
            $("#mult input:not([type='hidden'])").each(function(iter) {
                if (m && m.length === 2) {
                    obj = {
                        'answer_key' : iter,
                        'answer_value' : this.value,     // the  second match, which is the word inside |...|
                        'correct': true
                    };
                    acc.push(obj);
                }else {
                    alert("Please include a |answer| formatted sentence");
                }
            });
            send_data(acc);   // sending off common form info
        });
        
    }
    function register_coding_hooks() {
		$('#add_btn').click(function(e) {
			e.preventDefault();
			var clone = $('.inout:first').clone();
			clone.children().val("");
            clone.append('<button type="button" class="btn remove_button">Remove</button>');
			$('#inoutlist').append(clone);
		});
        $('#inoutlist').on("click", ".remove_button", function(e) {
            $(this).parent().remove();
        });
		$('form').submit(function(e) {
			
			e.preventDefault();
			var acc = [];   // collect answers
			var answer;
			$('ul .inout').each(function(idx) {
				var input = $(this).find(".in");
				var output = $(this).find(".out");
				answer = {
					answer_key: input.val(),
					answer_value: output.val(),
					correct: true
				}
				acc.push(answer);
			});
            send_data(acc);   // sending off common form info
		});
    }
        // multiple choice
	    jQuery("#action-1").click(function(e){
	    	$("#mult").html("");
			$("#mult").append('<div class="form-group"><input type="text" name="mult_answer" class="form-control flat" placeholder="Enter Correct Answer Here" required autofocus></div>');
			$("#mult").append('<div class="form-group"><input type="text" name="opt1" class="form-control flat" placeholder="Option 1" required></div>');
			$("#mult").append('<div class="form-group"><input type="text" name="opt2" class="form-control flat" placeholder="Option 2" required autofocus></div>');
			$("#mult").append('<div class="form-group"><input type="text" name="opt3" class="form-control flat" placeholder="Option 3" required></div>'); 
            $("#mult").append("<input id='qtype' type='hidden' name='qtype' value='multiple'>");
            register_multi_hooks();
		});
        // true false
	    jQuery("#action-2").click(function(e){
	    	$("#mult").html("");
            $("#mult").append('<label class="radio checked"><span class="icons"><span class="first-icon fui-radio-unchecked"></span><span class="second-icon fui-radio-checked"></span></span><input type="radio" name="group1" value="True" data-toggle="radio" checked>Answer is True</label>');
            $("#mult").append('<label class="radio"><span class="icons"><span class="first-icon fui-radio-unchecked"></span><span class="second-icon fui-radio-checked"></span></span><input type="radio" name="group1" value="False" data-toggle="radio">Answer is False</label>');
            $("#mult").append("<input id='qtype' type='hidden' name='qtype' value='true-false'>");
            register_truefalse_hooks();
		});
        // open-ended
		jQuery("#action-3").click(function(e){
			$("#mult").html("");
			$("#mult").append('<div class="form-group"><input type="text" name="fill_answer" class="form-control flat" placeholder="Enter Correct Answer Here" required autofocus></div>');
            $("#mult").append("<input id='qtype' type='hidden' name='qtype' value='fill'>");
            register_fill_hooks();
		});
        // coding question
		jQuery("#action-4").click(function(e){
      var html = '<button id="add_btn" class="btn">Add</button><ul id="inoutlist"><li class="inout"><input type="text" class="in" placeholder="Input"><input type="text" class="out" placeholder="Output"></li></ul>';
			$("#mult").html(html);
      $("#mult").append("<input id='qtype' type='hidden' name='qtype' value='coding'>");
            register_coding_hooks();
		});
	</script>
<?php include('footer.php');?>
</body>
</html>