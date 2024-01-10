{if $status == CurrentStatus::NotAuthorized}
    You must be <a href="register.php">registered</a> and
    <a href="login.php">logged in</a> to access this area.
{elseif $status == CurrentStatus::InvalidRequestType}
    <h2>Process Error </h2>
    <p class="pt-1">
        Invalid Request Type.  
        Please <a href="support.php">contact customer support for help</a>
    </p>
{elseif $status == CurrentStatus::TicketExpired}
    <h2>Ticket Submission Error </h2>
    <p class="pt-1">
        Your previous {$program} Certificate has expired.
        Please <a href="support.php">contact customer support for help</a>
        in getting a new copy of the course and an updated certificate. God bless.
    </p>
{elseif $status == CurrentStatus::TicketActive}
    Your previous {$program} Certificate Request has already been received.
    If you need to change any of the information on your submission,
    please <a href="/support.php">contact customer support for assistance</a>. God bless.
{elseif $status == CurrentStatus::NotFoundValidQuiz}
    <p>No valid Quiz Results available.</p>
    <h2><strong>Step 1: Purchase the {$program} Prep Program.</strong></h2>
    <h2><strong>Step 2: Take the Lesson and Take the Quiz</a>.</strong></h2>
    <h2><strong>Step 3: Use the Order Request Form link included at the top of the Quiz.</strong></h2>
{elseif $status == CurrentStatus::NotFoundDeliveryFee}
    <p><big>
        Please order a 
        {if $smarty.const.REQUEST_TYPE==2}
        <a class="shop-pop-up-button" data-redirect="/successPurchase.php" href="/shop/lessons_detail.php?id=598">
        {else}
        <a class="shop-pop-up-button" data-redirect="/successPurchase.php" href="/shop/lessons_detail.php?id=608">
        {/if}
            standard processing fee by clicking here and completing the check out.
        </a> 
        You will then be redirected to request your certificate. Certificates will be issued 2 to 3 business days after request.
        You may also order 
        <a class="shop-pop-up-button" data-redirect="/successPurchase.php" href="/shop/lessons_detail.php?id=599">
            expedited processing (to receive it within 24 hours)
        </a> 
        and/or
        <a class="shop-pop-up-button" data-redirect="/successPurchase.php" href="/shop/lessons_detail.php?id=604">
            a mailed hard copy sent via the US Postal Service
        </a>. 
    </p>
    <script src="/scripts/shop-pop-up-button.js"></script>
{elseif $status == CurrentStatus::NotFoundInternationalMailingFee}
    <p><big>
        All certificates physically mailed outside of the United States and its territories require the international upcharge fee to cover the higher cost of postage. 
        <a class="shop-pop-up-button" data-redirect="/successPurchase.php" href="/shop/lessons_detail.php?id=605">
            Please obtain that to proceed with your order
        </a>
    </p>
    <script src="/scripts/shop-pop-up-button.js"></script>
{elseif $status==CurrentStatus::SuccessfulCompletion}
    {$smarty.const.HOLIDAY_MESSAGE}
    <h2>Thank you for your Certificate Request to CatechismClass.com</h2>
    <p>This message confirms that we have received your request.
        For standard requests, your certificate will be processed and emailed to you as a PDF file
        within 2 - 3 business days. For expedited orders, your certificate will be emailed to you
        within 24 hours. Please note, we do not expedite standard orders and we do not guarantee
        any faster delivery for expedited certificates than 24 hours from the time of the request.
    </p>
    <p>Sometimes, our certificates end up in spam or junk folders.
        We encourage you to check your spam folder if it does not arrive in your inbox
        in the expected timeframe. Also, to help avoid the spam classification, please add
        service@catechismclass.com to your email address book.
    </p><p>After your certificate request is processed, a PDF copy will also be placed in the "My Certificates" section of your account.</p>
    <p>God bless,</p>
{elseif $status==CurrentStatus::ExpiredPurchase}
    <h2>Expired Purchase</h2>
    <p>You are requesting a new Certificate against an expired purchase.</p>
    {if isset($lesson)}
        <p>Please purchase another Lesson here: 
            <a href="/shop/lessons_detail.php?id={$lesson}" class="btn btn-success mt-2 mb-3">
                Purchase New {$program} Lesson
            </a>
        </p>
    {elseif isset($series)}
        <p>Please purchase another Series here: 
            <a href="/shop/series_detail.php?id={$series}" class="btn btn-success mt-2 mb-3">
                Purchase New {$program} Series
            </a>
        </p>
    {elseif isset($package)}
        <p>Please purchase another Package here: 
            <a href="/shop/package_detail.php?id={$package}" class="btn btn-success mt-2 mb-3">
                Purchase New {$program} Package
            </a>
        </p>
    {/if}
{else}
    <p>Fatal Error has occured.
        Please <a href="support.php">contact customer support for help</a>
    </p>
    {if $smarty.const.TEST_VALIDATION}
        {$error_message}
    {/if}
{/if}