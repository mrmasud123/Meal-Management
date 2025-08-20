$(document).ready(function(){

    $('#selectAll').change(function() {
        var isChecked = $(this).prop('checked');

        $('.mill_member_check').prop('checked', isChecked);
        
    });

    //Meal routine check
    $.ajax({
        url:'actions.php',
        data:{checkMealRoutine:1},
        method:"POST",
        dataType:'json',
        success:function(data){
            var res=data;
            console.log(res.data.length==0? "Empty": res.data);
            var element=`<h2 class="mt-2 mb-2">Meal Routine</h2><form id="meal_routineform">
                                    <div class="form-group">
                                        <input name="weekday1" type="text" value="শনিবার" readonly>
                                        <input name="weekday1day" type="text" placeholder="Day" value="${res.data.length==0? "":res.data[0].day_item}"   class="form-control">
                                        <input name="weekday1night" type="text" placeholder="Night" value ="${res.data.length==0? "":res.data[0].night_item}"  class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <input name="weekday2" type="text" value="রবিবার" readonly>
                                        <input name="weekday2day" type="text" placeholder="Day" value="${res.data.length==0? "":res.data[1].day_item}"  class="form-control">
                                        <input name="weekday2night" type="text" placeholder="Night" value="${res.data.length==0? "":res.data[1].night_item}" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <input name="weekday3" type="text" value="সোমবার" readonly>
                                        <input value="${res.data.length==0? "":res.data[2].day_item}"  name="weekday3day" type="text" placeholder="Day" class="form-control">
                                        <input value="${res.data.length==0? "":res.data[2].night_item}"  name="weekday3night" type="text" placeholder="Night" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <input name="weekday4" type="text" value="মঙ্গলবার" readonly>
                                        <input value="${res.data.length==0? "":res.data[3].day_item}"  name="weekday4day" type="text" placeholder="Day" class="form-control">
                                        <input value="${res.data.length==0? "":res.data[3].night_item}"  name="weekday4night" type="text" placeholder="Night" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <input name="weekday5" type="text" value="বুধবার" readonly>
                                        <input value="${res.data.length==0? "":res.data[4].day_item}"  name="weekday5day" type="text" placeholder="Day" class="form-control">
                                        <input value="${res.data.length==0? "":res.data[4].night_item}"  name="weekday5night" type="text" placeholder="Night" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <input name="weekday6" type="text" value="বৃহষ্পতিবার" readonly>
                                        <input value="${res.data.length==0? "":res.data[5].day_item}"  name="weekday6day" type="text" placeholder="Day" class="form-control">
                                        <input value="${res.data.length==0? "":res.data[5].night_item}"  name="weekday6night" type="text" placeholder="Night" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <input name="weekday7" type="text" value="শুক্রবার" readonly>
                                        <input value="${res.data.length==0? "":res.data[6].day_item}"  name="weekday7day" type="text" placeholder="Day" class="form-control">
                                        <input value="${res.data.length==0? "":res.data[6].night_item}"  name="weekday7night" type="text" placeholder="Night" class="form-control">
                                    </div>

                                    <button class="btn btn-primary btn-sm" type="submit">${res.data.length==0? "Add":"Update"}</button>
                                </form>`;
                                $(".meal_routine").html(element);
                                var meal_table="";
                                if(res.data.length==0){
                                    meal_table=`<tr><td colspan='3' align='center'>No data found</td></tr>`;
                                }else{
                                    res.data.forEach(element => {
                                        meal_table+=`<tr><td>${element.day}</td><td>${element.day_item}</td><td>${element.night_item}</td></tr>`;
                                    });
                                }
                                $('.meal_table table tbody').append(meal_table);
                                //Meal routine
                                $("#meal_routineform").on('submit',function(e){
                                    e.preventDefault();
                                    var formdata=new FormData(this);
                                    formdata.append('meal_routine',1);
                                    $.ajax({
                                        url:"actions.php",
                                        method:"POST",
                                        contentType:false,
                                        processData:false,
                                        dataType:"json",
                                        data:formdata,
                                        success:function(res){
                                            console.log(res);
                                            if(res){
                                                window.location.reload();
                                            }
                                        }
                                    });
                                });
        }
    });    

    $('select[name="updateType"]').on('change',(e)=>{
        e.preventDefault();
        $updateType=$('select[name="updateType"]').val();
        if($updateType=='mill'){
            $(".millForm").css('display',"block");
            $(".bazarForm").css("display","none");
            $(".memberForm").css("display","none");
            $(".expenseForm").css('display','none');
            $(".meal_routine").css("display","none");
        }else if($updateType=='bazar'){
            $(".millForm").css('display',"none");
            $(".bazarForm").css("display","block");
            $(".memberForm").css("display","none");
            $(".expenseForm").css('display','none');
            $(".meal_routine").css("display","none");
        }else if($updateType=="member"){
            $(".millForm").css('display',"none");
            $(".bazarForm").css("display","none");
            $(".memberForm").css("display","block");
            $(".expenseForm").css('display','none');
            $(".meal_routine").css("display","none");
        }else if($updateType=="expense"){
            $(".millForm").css('display',"none");
            $(".bazarForm").css("display","none");
            $(".memberForm").css("display","none");
            $(".expenseForm").css("display","block");
            $(".meal_routine").css("display","none");
        }else if($updateType=="meal_routine"){
            $(".millForm").css('display',"none");
            $(".bazarForm").css("display","none");
            $(".memberForm").css("display","none");
            $(".expenseForm").css("display","none");
            $(".meal_routine").css("display","block");
        }
    });

    $("#member").on('change',(e)=>{
            member_name=$("#member").val();
            // if(mbmType=="member" && member_name!=""){
                var formData={
                    'member_update':1,
                    'member_id':member_name
                };
                $.ajax({
                    url:"actions.php",
                    method:"POST",
                    data:formData,
                    success:function(res){
                        var data=res;
                        $(".record-form").css('display',"block");
                        $(".record-form").html(data);
                        $(".deleteMember").on("click",function(e){
                            var deleteMemberId=$(this).attr('data-memberId');
                            var formData={
                                'deleteMember':1,
                                'MemId':deleteMemberId
                            };
                            $.ajax({
                                url:'actions.php',
                                method:'post',
                                data:formData,
                            dataType:'json',
                            success:function(res){
                                var data=res;
                                if(res.success==1){
                                    $(".record-form").prepend(`<h5 id='error_msg'><span class='badge bg-success'>Member Deleted.</span></h5>`);
                                    setTimeout(function(){
                                        $('#error_msg').remove();
                                        location.reload();
                                    },2000);
                                                        
                                }
                            }
                            });
                        });
                        $("#memberUpdateForm").on('submit',function(e){
                            e.preventDefault();
                            var m_id=$(this).attr('data-memberId');
                            var formData=new FormData(this);
                            formData.append('m_id', m_id);
                            formData.append('updateMember',1);
                            $.ajax({
                                url:'actions.php',
                                method:"post",
                                contentType:false,
                                processData:false,
                                data:formData,
                                dataType:'json',
                                success:function(res){
                                    var data=res;
                                    console.log(res);
                                    if(res.success==1){
                                        $(".record-form").prepend(`<h5 id='error_msg'><span class='badge bg-success'>Member Updated.</span></h5>`);
                                        setTimeout(function(){
                                            $('#error_msg').remove();
                                            location.href="index.php";
                                        },2000);
                                                            
                                    }else{
                                        $(".record-form").prepend(`<h5 id='error_msg'><span class='badge bg-danger'>${res.error}</span></h5>`);
                                        setTimeout(function(){
                                            $('#error_msg').remove();
                                        },2000);
                                    }
                                }
                            });
                        });
                    }
                // });
            //  }
        });
    });
 
    //add member
    $("#addMemberForm").on('submit',function(e){
        e.preventDefault();
        var formData=new FormData(this);
        formData.append('addMember',1);
        $.ajax({
            url:'actions.php',
            method:"POST",
            data:formData,
            processData:false,
            contentType:false,
            dataType:'json',
            success:function(res){
                var data=res;
                if(res.success==1){
                    $(".memberForm").prepend(`<h5 id='error_msg'><span class='badge bg-success'>Member Inserted.</span></h5>`);
                    setTimeout(function(){
                        $('#error_msg').remove();
                    },2000);
                    $('#memberName').val("");
                    $('#memberEmail').val("");
                                        
                }else{
                    $(".memberForm").prepend(`<h5 id='error_msg'><span class='badge bg-danger'>${res.error}</span></h5>`);
                    setTimeout(function(){
                        $('#error_msg').remove();
                    },2000);
                }
                // console.log(typeof data);
            }
        });
    });

    //insert Bazar amount 
    $("#bazarAmtForm").on('submit',function(e){
        e.preventDefault();
        var bazar_member_checked_arr=[];
        $('.bazar_member_checked').each(function(index,element){
            if($(this).prop('checked')){
                bazar_member_checked_arr.push($(element).attr('data-bazar-id'));
            }
        });
        
        var formData=new FormData(this);
        formData.append('bazar', 1);
        formData.append('bazar_members', bazar_member_checked_arr);
        $.ajax({
            url:'actions.php',
            method:"POST",
            data:formData,
            processData:false,
            contentType:false,
            dataType:'json',
            success:function(res){
                if(res.success==1){
                    $(".bazarForm").prepend(`<h5 id='error_msg'><span class='badge bg-success'>Bazar Inserted.</span></h5>`);
                    setTimeout(function(){
                        $('#error_msg').remove();
                    },2000);
                                        
                }else{
                    $(".bazarForm").prepend(`<h5 id='error_msg'><span class='badge bg-danger'>${res.error}</span></h5>`);
                    setTimeout(function(){
                        $('#error_msg').remove();
                    },2000);
                }
            }
        });
    });

    //Mill Insert
    $("#millFormSubmit").on('submit',function(e){
        e.preventDefault();
        var mill_member_checked_arr=[];
        $('.mill_member_check').each(function(index,element){
            if($(this).prop('checked')){
                mill_member_checked_arr.push($(element).attr('data-miller-id'));
            }
        });
        console.log(mill_member_checked_arr);
        var formData=new FormData(this);
        formData.append('mill', 1);
        formData.append('mill_members', mill_member_checked_arr);
        $.ajax({
            url:'actions.php',
            method:"POST",
            data:formData,
            processData:false,
            contentType:false,
            dataType:'json',
            success:function(res){
                if(res.success==1){
                    $(".millForm").prepend(`<h5 id='error_msg'><span class='badge bg-success'>Mill Inserted.</span></h5>`);
                    setTimeout(function(){
                        $('#error_msg').remove();
                    },2000);
                                        
                }else{
                    $(".millForm").prepend(`<h5 id='error_msg'><span class='badge bg-danger'>${res.error}</span></h5>`);
                    setTimeout(function(){
                        $('#error_msg').remove();
                    },2000);
                }
            }
        });
    });

    //Mill update
    $(".mill_check_cnt").on('change',function(e){
        var formData={
            'millCount':$(this).val(),
            'mdate':$(this).attr('data-millDate'),
            'mname':$(this).attr('data-memberName'),
            'update_mill':1
        }

        $.ajax({
            url:'actions.php',
            method:'post',
            data:formData,
            dataType:'json',
            success:function(res){
                var data=res;
                if(res.success==1){
                    $("#millUpdateForm").prepend(`<h5 id='error_msg'><span class='badge bg-success'>Mill Updated.</span></h5>`);
                    setTimeout(function(){
                        $('#error_msg').remove();
                    },2000);
                                        
                }else{
                    $("#millUpdateForm").prepend(`<h5 id='error_msg'><span class='badge bg-danger'>${res.error}</span></h5>`);
                    setTimeout(function(){
                        $('#error_msg').remove();
                    },2000);
                }
            }
        });

    });

    //Bazar update
    $(".bazar_check_cnt").on('change',function(e){
        var formData={
            'bazarCount':$(this).val(),
            'bdate':$(this).attr('data-bazarDate'),
            'mname':$(this).attr('data-memberName'),
            'update_bazar':1
        }

        $.ajax({
            url:'actions.php',
            method:'post',
            data:formData,
            dataType:'json',
            success:function(res){
                var data=res;
                if(res.success==1){
                    $("#bazarUpdateForm").prepend(`<h5 id='error_msg'><span class='badge bg-success'>Bazar Updated.</span></h5>`);
                    setTimeout(function(){
                        $('#error_msg').remove();
                    },2000);
                                        
                }else{
                    $("#bazarUpdateForm").prepend(`<h5 id='error_msg'><span class='badge bg-danger'>${res.error}</span></h5>`);
                    setTimeout(function(){
                        $('#error_msg').remove();
                    },2000);
                }
            }
        });
    });

    let tableData='';
    //Calculate flat credentials
    $("#monthlyExpenseForm").on('submit', function(e) {
        e.preventDefault();
        const $formdata = new FormData(this);
        $formdata.append('calculate_flat_credentials', 1);
        
        $.ajax({
            url: 'actions.php',
            method: "POST",
            data: $formdata,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(res) {
                if (res.success == 1) {
                    const tableData = res.data;
                    $('.modal-body').html(res.data);
                    $("#mill_expense_modal").fadeIn(500);
                } else {
                    $("#monthlyExpenseForm").prepend(`<h5 id='error_msg'><span class='badge bg-danger'>${res.error}</span></h5>`);
                    setTimeout(function() {
                        $('#error_msg').remove();
                    }, 2000);
                }
            }
        });
    });
    

    
    $(".modal_close_btn").on('click',()=>{
        $("#mill_expense_modal").fadeOut(500);
    });


    $("#flatCredentialsForm").on('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        formData.append('insert_flat_credentials', 1);

        $.ajax({
            url: 'actions.php',
            method: 'POST',
            data: formData,
            contentType: false,     
            processData: false, 
            dataType:"json",   
            success: function(res) {
                if(res.success==1){
                    $("#flatCredentialsForm").prepend(`<h5 id='error_msg'><span class='badge bg-success'>Seat rent updated.</span></h5>`);
                    setTimeout(function(){
                        $('#error_msg').remove();
                    },2000);
                                        
                }else{
                    $("#flatCredentialsForm").prepend(`<h5 id='error_msg'><span class='badge bg-danger'>${res.error}</span></h5>`);
                    setTimeout(function(){
                        $('#error_msg').remove();
                    },2000);
                }
            },
            error: function(xhr, status, error) {
                console.error("❌ AJAX error:", error);
                console.log("Response:", xhr.responseText);
            }
        });

    
});


    
});