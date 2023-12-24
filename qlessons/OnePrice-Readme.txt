/* // most recent edit - 6-29-16; to include dual pricing modules. */

Baptism Quiz and Certificate Process...

1) add_doc_flags.php -- enters doc flags to the quiz display, writes them to the DB, used in all quizzes with 'doctrinal questions'.

2) adult-pass-fail.php -- inserts a note on screen mentioning the possibility of a needed re-take of the quiz.

3) bap_doctrine.php -- determines 'doctrinal errors' in the Baptism Quiz.. @ $1995, provides links to purchase the remedial lessons. @ $2995 auto-writes the remedial lessons to the DB as needed, and also auto-writes the Standard Processing Fee. 

3) baptism_process.php -- In all cases, when a remedial lesson is scored, this inserts a message if the quiz has not been passed and allows a link to re-set the quiz.. Contains the logic that returns to the Baptism Quiz with messages of Remedial Satisfied or not.

4) bap_OnePrice.php -- this tests to see if the purchase was made on $1995 or $2995. Sets the var $OnePrice for use in the local scope. Includes 'globals' for $oldPrice and $currentPrice for quick Price edits.

5) baptism-hint.php -- provides the 3 necessary messages for the various conditions based on $OnePrice @ $2995 without Expedited Fee, or $OnePrice @ $2995 with Expedited Fee; or $OnePrice @ $1995. 

6) baptism-pass-fail.php -- provides the pass/fail message for the Baptism Quiz, 100% score contains the link to the Order Form.

7) baptism_retake.php -- grants the user a 'self retake' link when remedial criteria is met. 

8) doctrine.php -- puts a message on the screen based on 'doctrinal errors'. This is used in all quizzes. 

9) general-pass-hail.php -- puts a general message on screen that a quiz has scored below 70% 

10) l-28_retake.php -- a link to this appears at the end of the quiz for lesson 28 and redirects the user to allow a retake of lesson 28.

11) l-9_retake.php -- a link to this appears at the end of the quiz for lesson 9 and redirects the user to allow a retake of lesson 9.

12) order_text.php -- generates the message format to be sent to the Admin address for the Cert Order Form.

13) pass-fail-test.php -- a general switch to direct the quiz process into the proper 'notifications' screen.

14) quiz-hint.php -- used in all files, to give a message on the quiz screen about 'system time outs' and how to avoid that.

15) remedial85.php -- the system array used in scoring the Baptism quiz.

16) quiz.php -- used in all lessons, but contains embedded calls to the Baptism Process if it is the Baptism Lesson = 85. Also containg redirects to the remedial lessons logic when needed.

17) baptism-cert-request.php -- the Order Form required to order a certificate.

18) baptism-cert-support-ticket.php -- the file doing the processing of the Cert Request.

19) README.txt notes about where these files are 

20) OnePrice-Readme,txt -- this file.


