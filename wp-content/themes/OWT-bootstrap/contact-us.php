<?php
/*
  Template Name: Contact Us
 */
?>
<?php get_header(); ?>

<script type="text/javascript" language="Javascript1.1"> 


// <!-- Begin 
var bCancel = false; 

function validateContactUsForm(form) {                                                                   

    if (bCancel) 
        return true; 
    else 
        return validateEmail(form) && validateRequired(form); 
} 



function email () { 
    this.aa = new Array("replyToAddress", "Your E-mail address is an invalid e-mail address.", new Function ("varName", " return this[varName];"));
} 



function required () { 

    this.aa = new Array("replyToAddress", "Your E-mail address is required.", new Function ("varName", " return this[varName];"));

    this.ab = new Array("subject", "A subject line is required.", new Function ("varName", " return this[varName];"));

    this.ac = new Array("message", "A message is required.", new Function ("varName", " return this[varName];"));

} 

function validateFloatRange(form) {

    oRange = new floatRange();

    for (x in oRange) {

        var field = form[oRange[x][0]];

        // Skip to the next test if this is not the field we are currently testing
        if((currentTestField!="")&&(field.name != currentTestField)) continue;

        // Text and textarea only. Don't check for required here (ie empty field is ok)
        if (((field.type != 'text') && (field.type != 'textarea')) || (field.value.length == 0)) return true;

        var fMin = parseFloat(oRange[x][2]("min"));
        var fMax = parseFloat(oRange[x][2]("max"));
        var fValue = parseFloat(form[oRange[x][0]].value);

        if ((fValue >= fMin) && (fValue <= fMax)) return true;

        // Bad value found

        setFocus(field,oRange[x][1]);
        doAlert(field,oRange[x][1]);

        return false;

    }

    return true;

}

function validateMaxLength(form) {

    oMaxLength = new maxlength();

    for (x in oMaxLength) {

        // only continue if this is the field we are currently testing

        var field = form[oMaxLength[x][0]];

        if((currentTestField!="")&&(field.name != currentTestField)) continue;



        // if the field is empty, ignore the test; if the field is required,

        // that's a separate test and not the responsibility of this test

        if ( field.value.length == 0 ) return true;



        if (field.type == 'text' || field.type == 'textarea' || field.type == 'password') {

            var iMax = parseInt(oMaxLength[x][2]("maxlength"));

            if (field.value.length > iMax) {

                setFocus(field,oMaxLength[x][1]);

                doAlert(field,oMaxLength[x][1]);

                return false;

            }

        }

    }

    return true;

}

// General routines needed by everything. Works because if 1 validation is used,

// all code is loaded. Won't work for struts 1.2?



// Global variables

var EMPTYFIELD = "";

var currentTestField = "";

var previousTestField = "";

			

// doAlert: can be overwritten if a different popup method is needed.

// the field argument is not used, but it can be when you overwrite doAlert with your local version

function doAlert(field,message) {

    alert(message);

}

// isAllDigits

function isAllDigits(argvalue) {

    argvalue = argvalue.toString();

    var validChars = "0123456789";

    var startFrom = 0;

    if (argvalue.substring(0, 2) == "0x") {

        validChars = "0123456789abcdefABCDEF";

        startFrom = 2;

    } else if (argvalue.charAt(0) == "0") {

        validChars = "01234567";

        startFrom = 1;

    }

    for (var n = 0; n < argvalue.length; n++) {

        if (validChars.indexOf(argvalue.substring(n, n+1)) == -1) return false;

    }

    return true;

}

// setFocus: can be overwritten if a different focus  method is needed.

// the message argument is not used, but it can be when you overwrite setFocus with your local version 

function setFocus(field,message) {

    field.focus();

}

            

// disableAfterSubmit

function disableAfterSubmit() {

    var allTags = document.all? document.all : document.getElementsByTagName("*")

    if (allTags[0].className != null)

        for (i=0; i<allTags.length; i++)

            if (allTags[i].className.toLowerCase() == "disableaftersubmit" )

                allTags[i].disabled = true;

    return true;

} 

		           

// validateOrderedTests

function validateOrderedTests(form, validateTests) {

    // bCancel prevents validation, but not the disableAfterSubmit

    if(!bCancel){

        previousTestField = "";

        for (i =0; i < form.elements.length; i++) {

            // fieldsets are returned as an element with type undefined!!?

            if (form.elements[i].type != undefined && form.elements[i].type != "submit" && form.elements[i].type != "reset") {

                var field = form.elements[i];

                currentTestField = field.name;

                if ( currentTestField == previousTestField ) continue;

                previousTestField = currentTestField;

                if ( !eval(validateTests + "(form)") ) return false;

            }

        }

    }

    // If we're here, the validations passed. Before submitting, disable all elements in class 'disableAfterSubmit'

    disableAfterSubmit();

    return true;

}



// validateRequired

function validateRequired(form) {

    oRequired = new required();

    for (x in oRequired) {

        var field = form[oRequired[x][0]];

        var isMultiple = false;



        // multi-fields (radio buttons, checkboxes with same name) are in arrays

        if (field.type == undefined) {

            field = form[oRequired[x][0]][0];

            isMultiple = true;

        }



        // only continue if this is the field we are currently testing

        if((currentTestField!="")&&(field.name != currentTestField)) continue;



        var value = '';

        // for dropdowns, find index of selected if any

        if (field.type == "select-one") {

            var si = field.selectedIndex;

            if (si >= 0) {

                value = field.options[si].value;

            }

        // for multi-fields, see if one is selected

        } else if ( isMultiple ) {

            for (var j=0; form[oRequired[x][0]][j] != undefined; j++) {

                field = form[oRequired[x][0]][j];

                if (field.checked) value = field.value;

            }

        // otherwise, get field's value

        } else {

            value = field.value;

        }



        // if no value found, report the error and move focus to the field

        if (value == '') {

            setFocus(field,oRequired[x][1]);

            doAlert(field,oRequired[x][1]);

            return false;

        }

    } // for

    return true;

}

function validateInteger(form) {

    oInteger = new IntegerValidations();

    for (x in oInteger) {

        var field = form[oInteger[x][0]];



        // only continue if this is the field we are currently testing

        if((currentTestField!="")&&(field.name != currentTestField)) continue;



        if (field.type == 'text' || field.type == 'textarea') {



            // get field's value

            var value = field.value;

            if (value.length > 0) {



                if (!isAllDigits(value)) {

                    setFocus(field,oInteger[x][1]);

                    doAlert(field,oInteger[x][1]);

                    return false;

                }

                var iValue = parseInt(value);

                if (isNaN(iValue) || !(iValue >= -2147483648 && iValue <= 2147483647)) {

                    setFocus(field,oInteger[x][1]);

                    doAlert(field,oInteger[x][1]);

                    return false;

                }

            }		// if value.length

        }			// if field.type

    }				// for

    return true;

}

function validateNonDefaultValue(form) {

    oDefault = new NonDefaultValue();

    for (x in oDefault) {

        var field = form[oDefault[x][0]];

		

        // Skip to the next test if this is not the field we are currently testing

        if((currentTestField!="")&&(field.name != currentTestField)) continue;

				

        // if field is empty, ignore the test; if the field is required,

        // that's a separate test and not the responsibility of this test

        if ( field.value.length == 0 ) return true;

				

        // if the field value equals the default field value (stored in variable 

        // [formName]_[fieldName]_defaultValue), test fails

        if ( field.value == (eval(form.name + "_" + field.name + "_defaultValue" )) ) {

            setFocus(field,oDefault[x][1]);

            doAlert(field,oDefault[x][1]);

            return false;

        }

    } 		// for

    return true;		

}

function validateDate(form) {

    var bValid = true;

    oDate = new DateValidations();

    for (x in oDate) {

 

        // only continue if this is the field we are currently testing

        var field = form[oDate[x][0]];

        if((currentTestField!="")&&(field.name != currentTestField)) continue;



        var value = field.value;

        var datePattern = oDate[x][2]("datePatternStrict");

        if ((field.type == 'text' || field.type == 'textarea') &&

            (value.length > 0) &&

            (datePattern.length > 0)) {

            var MONTH = "MM";

            var DAY = "dd";

            var YEAR = "yyyy";

            var orderMonth = datePattern.indexOf(MONTH);

            var orderDay = datePattern.indexOf(DAY);

            var orderYear = datePattern.indexOf(YEAR);

            if ((orderDay < orderYear && orderDay > orderMonth)) {

                var iDelim1 = orderMonth + MONTH.length;

                var iDelim2 = orderDay + DAY.length;

                var delim1 = datePattern.substring(iDelim1, iDelim1 + 1);

                var delim2 = datePattern.substring(iDelim2, iDelim2 + 1);

                if (iDelim1 == orderDay && iDelim2 == orderYear) {

                    dateRegexp = new RegExp("^(\\d{2})(\\d{2})(\\d{4})$");

                } else if (iDelim1 == orderDay) {

                    dateRegexp = new RegExp("^(\\d{2})(\\d{2})[" + delim2 + "](\\d{4})$");

                } else if (iDelim2 == orderYear) {

                    dateRegexp = new RegExp("^(\\d{2})[" + delim1 + "](\\d{2})(\\d{4})$");

                } else {

                    dateRegexp = new RegExp("^(\\d{2})[" + delim1 + "](\\d{2})[" + delim2 + "](\\d{4})$");

                }

                var matched = dateRegexp.exec(value);

                if(matched != null) {

                    if (!isValidDate(matched[2], matched[1], matched[3])) {

                        bValid =  false;

                    }

                } else {

                    bValid =  false;

                }

            } else if ((orderMonth < orderYear && orderMonth > orderDay)) {

                var iDelim1 = orderDay + DAY.length;

                var iDelim2 = orderMonth + MONTH.length;

                var delim1 = datePattern.substring(iDelim1, iDelim1 + 1);

                var delim2 = datePattern.substring(iDelim2, iDelim2 + 1);

                if (iDelim1 == orderMonth && iDelim2 == orderYear) {

                    dateRegexp = new RegExp("^(\\d{2})(\\d{2})(\\d{4})$");

                } else if (iDelim1 == orderMonth) {

                    dateRegexp = new RegExp("^(\\d{2})(\\d{2})[" + delim2 + "](\\d{4})$");

                } else if (iDelim2 == orderYear) {

                    dateRegexp = new RegExp("^(\\d{2})[" + delim1 + "](\\d{2})(\\d{4})$");

                } else {

                    dateRegexp = new RegExp("^(\\d{2})[" + delim1 + "](\\d{2})[" + delim2 + "](\\d{4})$");

                }

                var matched = dateRegexp.exec(value);

                if(matched != null) {

                    if (!isValidDate(matched[1], matched[2], matched[3])) {

                        bValid =  false;

                    }

                } else {

                    bValid =  false;

                }

            } else if ((orderMonth > orderYear && orderMonth < orderDay)) {

                var iDelim1 = orderYear + YEAR.length;

                var iDelim2 = orderMonth + MONTH.length;

                var delim1 = datePattern.substring(iDelim1, iDelim1 + 1);

                var delim2 = datePattern.substring(iDelim2, iDelim2 + 1);

                if (iDelim1 == orderMonth && iDelim2 == orderDay) {

                    dateRegexp = new RegExp("^(\\d{4})(\\d{2})(\\d{2})$");

                } else if (iDelim1 == orderMonth) {

                    dateRegexp = new RegExp("^(\\d{4})(\\d{2})[" + delim2 + "](\\d{2})$");

                } else if (iDelim2 == orderDay) {

                    dateRegexp = new RegExp("^(\\d{4})[" + delim1 + "](\\d{2})(\\d{2})$");

                } else {

                    dateRegexp = new RegExp("^(\\d{4})[" + delim1 + "](\\d{2})[" + delim2 + "](\\d{2})$");

                }

                var matched = dateRegexp.exec(value);

                if(matched != null) {

                    if (!isValidDate(matched[3], matched[2], matched[1])) {

                        bValid =  false;

                    }

                } else {

                    bValid =  false;

                }

            } else {

                bValid =  false;

            }

        }

    }

    if (!bValid) {

        setFocus(field,oDate[x][1]);

        doAlert(field,oDate[x][1]);

        return false;

    }

    return true;

}



function isValidDate(day, month, year) {

    if (month < 1 || month > 12) {

        return false;

    }

    if (day < 1 || day > 31) {

        return false;

    }

    if ((month == 4 || month == 6 || month == 9 || month == 11) &&

        (day == 31)) {

        return false;

    }

    if (month == 2) {

        var leap = (year % 4 == 0 &&

            (year % 100 != 0 || year % 400 == 0));

        if (day>29 || (day == 29 && !leap)) {

            return false;

        }

    }

    return true;

}

function validateIntRange(form) {

    oRange = new intRange();

    for (x in oRange) {

        var field = form[oRange[x][0]];

		

        // Skip to the next test if this is not the field we are currently testing

        if((currentTestField!="")&&(field.name != currentTestField)) continue;

		

        // Text and textarea only. Don't check for required here (ie empty field is ok)

        if (((field.type != 'text') && (field.type != 'textarea')) || (field.value.length == 0)) return true;

		

        var iMin = parseInt(oRange[x][2]("min"));

        var iMax = parseInt(oRange[x][2]("max"));

        var iValue = parseInt(form[oRange[x][0]].value);

        if ((iValue >= iMin) && (iValue <= iMax)) return true;

		

        // Bad value found

        setFocus(field,oRange[x][1]);

        doAlert(field,oRange[x][1]);

        return false;

    }

    return true;

}

function validateMatch(form) {

    oMatch = new MatchValidations();

    for (x in oMatch) {

        var field = form[oMatch[x][0]];

		

        // Skip to the next test if this is not the field we are currently testing

        if((currentTestField!="")&&(field.name != currentTestField)) continue;



        // if either field is empty, ignore the test; if the fields are required,

        // that's a separate test and not the responsibility of this test

        if ( field.value.length == 0 ) return true;

        var field2Value = eval("form." + oMatch[x][2]("secondProperty") + ".value");

        if ( field2Value.length == 0 ) return true;



        if ( field.value != field2Value ) {

            setFocus(field,oMatch[x][1]);

            doAlert(field,oMatch[x][1]);

            return false;

        }

    } 		// for

    return true;		

}

function validateFloat(form) {

    oFloat = new FloatValidations();

    for (x in oFloat) {

        var field = form[oFloat[x][0]];

 

        // only continue if this is the field we are currently testing

        if((currentTestField!="")&&(field.name != currentTestField)) continue;



        if (field.type == 'text' || field.type == 'textarea') {



            // get field's value

            var value = field.value;

                       

            if (value.length > 0) {

                        

                var iValue = parseFloat(value);

                if (!isAllDigits(value.replace(".","")) || isNaN(iValue)) {

                    setFocus(field,oFloat[x][1]);

                    doAlert(field,oFloat[x][1]);

                    return false;

                }

            }		// if value.length

        }			// if field.type

    }				// for

    return true;

}

function validateEmail(form) {

    oEmail = new email();

    for (x in oEmail) {

        var field = form[oEmail[x][0]];

		

        // Skip to the next test if this is not the field we are currently testing

        if((currentTestField!="")&&(field.name != currentTestField)) continue;

		

        // Text and textarea only. Don't check for required here (ie empty field is ok)

        if (((field.type != 'text') && (field.type != 'textarea')) || (field.value.length == 0)) return true;

		

        if (checkEmail(field.value)) return true;

		

        // Bad value found

        setFocus(field,oEmail[x][1]);

        doAlert(field,oEmail[x][1]);

        return false;

    }

    return true;

}



/**

* Reference: Sandeep V. Tamhankar (stamhankar@hotmail.com),

* http://javascript.internet.com

*/

function checkEmail(emailStr) {

    if (emailStr.length == 0) {

        return true;

    }

    var emailPat=/^(.+)@(.+)$/;

    var specialChars="\\(\\)<>@,;:\\\\\\\"\\.\\[\\]";

    var validChars="\[^\\s" + specialChars + "\]";

    var quotedUser="(\"[^\"]*\")";

    var ipDomainPat=/^(\d{1,3})[.](\d{1,3})[.](\d{1,3})[.](\d{1,3})$/;

    var atom=validChars + '+';

    var word="(" + atom + "|" + quotedUser + ")";

    var userPat=new RegExp("^" + word + "(\\." + word + ")*$");

    var domainPat=new RegExp("^" + atom + "(\\." + atom + ")*$");

    var matchArray=emailStr.match(emailPat);

    if (matchArray == null) {

        return false;

    }

    var user=matchArray[1];

    var domain=matchArray[2];

    if (user.match(userPat) == null) {

        return false;

    }

    var IPArray = domain.match(ipDomainPat);

    if (IPArray != null) {

        for (var i = 1; i <= 4; i++) {

            if (IPArray[i] > 255) {

                return false;

            }

        }

        return true;

    }

    var domainArray=domain.match(domainPat);

    if (domainArray == null) {

        return false;

    }

    var atomPat=new RegExp(atom,"g");

    var domArr=domain.match(atomPat);

    var len=domArr.length;

    if ((domArr[domArr.length-1].length < 2) ||

        (domArr[domArr.length-1].length > 3)) {

        return false;

    }

    if (len < 2) {

        return false;

    }

    return true;

}

function validateMask(form) {

    oMasked = new mask();

    for (x in oMasked) {

        var field = form[oMasked[x][0]];

 

        // only continue if this is the field we are currently testing

        if((currentTestField!="")&&(field.name != currentTestField)) continue;



        if ((field.type == 'text' || field.type == 'textarea' || field.type == 'password') &&

            (form[oMasked[x][0]].value.length > 0)) {

            if (!matchPattern(field.value, oMasked[x][2]("mask"))) {

                setFocus(field,oMasked[x][1]);

                doAlert(field,oMasked[x][1]);

                return false;

            }

        }

    }

    return true;

}

function matchPattern(value, mask) {

    var bMatched = mask.exec(value);

    if (!bMatched) {

        return false;

    }

    return true;

}

function validateMinLength(form) {

    oMinLength = new minlength();

    for (x in oMinLength) {



        // only continue if this is the field we are currently testing

        var field = form[oMinLength[x][0]];

        if((currentTestField!="")&&(field.name != currentTestField)) continue;



        // if the field is empty, ignore the test; if the field is required,

        // that's a separate test and not the responsibility of this test

        if ( field.value.length == 0 ) return true;



        if (field.type == 'text' || field.type == 'textarea' || field.type == 'password') {

            var iMin = parseInt(oMinLength[x][2]("minlength"));

            if (field.value.length < iMin) {

                setFocus(field,oMinLength[x][1]);

                doAlert(field,oMinLength[x][1]);

                return false;

            }

        }

    }

    return true;

}

function validateRequiredIf(form) {



    for (x =0; x < form.elements.length; x++) {

        if (form.elements[x].type != undefined && form.elements[x].type != "submit" && form.elements[x].type != "reset"){

            eval(form.elements[x].name + "= \"\";");

        }

    }

    var leadingComma = new RegExp("^,","g");

    var twoCommas = new RegExp(",,","");

    for (x =0; x < form.elements.length; x++) {

        if (form.elements[x].type != undefined && form.elements[x].type != "submit" && form.elements[x].type != "reset") {

            var field = form.elements[x]

            var fieldName = field.name;

            var fieldValue = field.value;

            if ( field.type == "radio" || field.type == "checkbox" ) fieldValue = ( field.checked ) ? field.value : "";

            // removing multi lines

            fieldValue = fieldValue.replace(/\r/g,"");

            fieldValue = fieldValue.replace(/\n/g,"");

            eval(fieldName + "+= (fieldValue != \"\") ? \"," + fieldValue + "\" : \"\";");

            eval(fieldName + "=" + fieldName + ".replace(leadingComma,\"\").replace(twoCommas,\",\")");

        }

    }



    oRequiredIf = new requiredif();

    for ( x in oRequiredIf ) {

        var field = form[oRequiredIf[x][0]];

        // multi-fields (radio buttons, checkboxes with same name) are in arrays

        if (field.type == undefined) field = form[oRequiredIf[x][0]][0];



        // only continue if this is the field we are currently testing

        if((currentTestField!="")&&(field.name != currentTestField)) continue;



        var test = oRequiredIf[x][2]("test");

        if ( !eval(test) || eval(field.name + "!= \"\"") ) return true;



        setFocus(field,oRequiredIf[x][1]);

        doAlert(field,oRequiredIf[x][1]);

        return false;

    }

    return true;

}



//End --> 
</script>

<div class="row">    
    <div class="col-md-9">

    <h2><?php the_title(); ?></h2>
    <div class="hr">&nbsp;</div>
    <fieldset class="fieldset contactPage">

        <p><?php _e('Send a message to the Department of Economic and Social Affairs.','site');?></p>
        <p><?php _e('Please enter the details of your message and click on Send.','site');?></p>

        <form name="contactUsForm" action="http://imdis.un.org/sendEmail.asp" onsubmit="return validateContactUsForm(this);" method="POST">

            <input type="hidden" name="dispatch" value="sendEmail" />
            <input type="hidden" name="toCode" value="6149" />
            <input type="hidden" name="replyToEdit" value="" />
            <input type="hidden" name="subjectEdit" value="" />
            <input type="hidden" name="messageEdit" value="" />

            <table summary="Table used for layout purposes of the contact us form">

                <tr>
                    <td valign="top" align="right">
                        <label for="replyToAddress">
                            <span class="required"><?php _e('Your email address','site');?>&nbsp;</span>
                        </label>
                    </td>
                    <td>
                        <input type="text" name="replyToAddress" size="50" placeholder="<?php _e('Enter your E-mail address here','site');?>" />
                    </td>
                </tr>
                <tr>
                    <td valign="top" align="right">
                        <label for="replyToName"><?php _e('Your name','site');?>&nbsp;</label>
                    </td>

                    <td>
                        <input type="text" name="replyToName" size="50" placeholder="<?php _e('Enter your name here','site');?>" />
                    </td>
                </tr>

                <tr>
                    <td valign="top" align="right"><label for="subject"><span class="required"><?php _e('Subject','site');?>&nbsp;</span></label></td>
                    <td>
                        <input type="text" name="subject" size="50" placeholder="<?php _e('Enter the subject of your message here','site');?>" />
                    </td>
                </tr>
                <tr>
                    <td valign="top" align="right"><label for="message"><span class="required"><?php _e('Message','site');?>&nbsp;</span></label></td>
                    <td>
                        <textarea name="message" cols="48" rows="10"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <input type="submit" value="<?php _e('Send','site');?>" class="submitBtn" />&nbsp;&nbsp;
                        <input type="button" value="<?php _e('Cancel','site');?>" onclick="if (window.opener != null) window.opener.focus(); self.close();" class="submitBtn" />

                    </td>
                </tr>
            </table>
        </form>
        <P>
            <?php _e('Thank you for your comments.','site');?>
        </P>
    </fieldset>
</div>
<?php get_sidebar('right'); ?>
</div><!-- end of #content -->


<?php get_footer(); ?>