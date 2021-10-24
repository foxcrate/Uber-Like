@extends('user.layout.app')

@section('content')

    @if(app()->getLocale()=='ar')
<div class="row gray-section no-margin" dir="rtl">
    <div class="container">
        <div class="content-block">
            <h2>{{ $title }}</h2>
            <div class="title-divider"></div>
            <p >{!! Setting::get($page) !!}</p>

            <section id="content" >
                نقدر مخاوفكم واهتمامكم بشأن خصوصية بياناتكم على شبكة الإنترنت.
                <br />
                لقد تم إعداد هذه السياسة لمساعدتكم في تفهم طبيعة البيانات التي نقوم بتجميعها منكم
                عند زيارتكم لموقعنا على شبكة الانترنت وكيفية تعاملنا مع هذه البيانات الشخصية.
                <br />
                <br />
                <b>
                    التصفح
                </b><br />
                لم نقم بتصميم هذا الموقع من أجل تجميع بياناتك الشخصية من جهاز الكمبيوتر الخاص بك
                أثناء تصفحك لهذا الموقع, وإنما سيتم فقط استخدام البيانات المقدمة من قبلك بمعرفتك ومحض إرادتك.
                <br />
                <br />
                <b>
                    عنوان بروتوكول شبكة الإنترنت (IP)
                </b>
                <br />&nbsp;
                في أي وقت تزور فيه اي موقع انترنت بما فيها هذا الموقع  , سيقوم السيرفر المضيف  بتسجيل عنوان بروتوكول شبكة الإنترنت
                &nbsp; (IP)
                الخاص بك , تاريخ ووقت الزيارة ونوع متصفح الإنترنت الذي تستخدمه والعنوان
                URL
                الخاص بأي موقع من مواقع الإنترنت التي تقوم بإحالتك إلى الى هذا الموقع على الشبكة.
                <br /><b>
                    <br />عمليات المسح على الشبكة
                    <br /></b>
                إن عمليات المسح التي نقوم بها مباشرة على الشبكة تمكننا من تجميع بيانات محددة
                مثل البيانات المطلوبة منك بخصوص نظرتك وشعورك تجاه موقعنا.تعتبر ردودك ذات أهمية قصوى ,
                ومحل تقديرنا حيث أنها تمكننا من تحسين مستوى موقعنا, ولك كامل الحرية والإختيار في تقديم
                البيانات المتعلقة بإسمك والبيانات الأخرى.
                <br />
                <br /><b>الروابط بالمواقع الأخرى على شبكة الإنترنت
                </b>
                <br />
                قد يشتمل موقعنا على روابط بالمواقع الأخرى على شبكة الإنترنت.
                او علانات من مواقع اخرى مثل
                <a href="https://www.google.com/adsense" target="_blank">
                    Google AdSense </a>
                ولا نعتبر  مسئولين عن أساليب تجميع البيانات من قبل تلك المواقع, يمكنك الاطلاع على سياسات
                السرية والمحتويات الخاصة بتلك المواقع التي يتم الدخول إليها من خلال أي رابط ضمن هذا الموقع.
                <br />
                <br />
                نحن قد نستعين بشركات إعلان لأطراف ثالثة لعرض الإعلانات عندما تزور موقعنا على الويب.
                يحق لهذه الشركات أن تستخدم معلومات حول زياراتك لهذا الموقع ولمواقع الويب الأخرى
                (باستثناء الاسم أو العنوان أو عنوان البريد الإلكتروني أو رقم الهاتف)، وذلك من أجل تقديم
                إعلانات حول البضائع والخدمات التي تهمك.
                إذا كنت ترغب في مزيد من المعلومات حول هذا الأمر وكذلك إذا كنت تريد معرفة الاختيارات
                المتاحة لك لمنع استخدام هذه المعلومات من قِبل هذه الشركات
                ،
                <a href="http://www.google.com/privacy_ads.html" target="_blank">
                    فالرجاء النقر هنا. </a>
                <br />
                <br /><b>
                    إفشاء المعلومات
                    <br /></b>
                سنحافظ  في كافة الأوقات على خصوصية وسرية كافة البيانات الشخصية التي نتحصل عليها.
                ولن يتم إفشاء هذه المعلومات إلا إذا كان ذلك مطلوباً بموجب أي قانون أو عندما نعتقد بحسن نية
                أن مثل هذا الإجراء سيكون مطلوباً أو مرغوباً فيه للتمشي مع القانون , أو للدفاع عن أو حماية حقوق
                الملكية الخاصة بهذا الموقع أو الجهات المستفيدة منه.
                <br />
                <br /><b>
                    البيانات اللازمة لتنفيذ المعاملات المطلوبة من قبلك
                    <br /></b>
                عندما نحتاج إلى أية بيانات خاصة بك , فإننا سنطلب منك تقديمها بمحض إرادتك.
                حيث ستساعدنا هذه المعلومات في الاتصال بك وتنفيذ طلباتك حيثما كان ذلك ممكنناً.
                لن يتم اطلاقاً بيع البيانات المقدمة من قبلك إلى أي طرف ثالث بغرض تسويقها لمصلحته
                الخاصة دون الحصول على موافقتك المسبقة والمكتوبة ما لم يتم ذلك على أساس أنها ضمن بيانات
                جماعية تستخدم للأغراض الإحصائية والأبحاث دون اشتمالها على أية بيانات من الممكن استخدامها للتعريف بك.
                <br />
                <br /><b>
                    عند الاتصال بنا
                    <br /></b>
                سيتم التعامل مع كافة البيانات المقدمة من قبلك على أساس أنها سرية . تتطلب النماذج التي يتم تقديمها
                مباشرة على الشبكة تقديم البيانات التي ستساعدنا في تحسين موقعنا.سيتم استخدام البيانات
                التي يتم تقديمها من قبلك في الرد
                على كافة استفساراتك , ملاحظاتك , أو طلباتك من قبل  هذا الموقع أو أيا من المواقع التابعة له .
                <br /><br /><b>
                    إفشاء المعلومات لأي طرف ثالث
                    <br /></b>
                لن نقوم ببيع , المتاجرة , تأجير , أو إفشاء أية معلومات لمصلحة أي طرف ثالث خارج هذا الموقع,
                أو  المواقع التابعة له.وسيتم الكشف عن المعلومات فقط في حالة صدور أمر بذلك من قبل أي سلطة قضائية أو تنظيمية.
                <br /><br /><b>
                    التعديلات على سياسة سرية وخصوصية المعلومات
                </b><br />نحتفظ بالحق في تعديل بنود وشروط سياسة سرية وخصوصية المعلومات إن لزم الأمر ومتى
                كان ذلك ملائماً. سيتم تنفيذ التعديلات هنا او على صفحة
                <a href="http://www.ebmark.com/privacy.html">
                    سياسة الخصوصية
                </a>
                الرئيسية وسيتم بصفة مستمرة إخطارك بالبيانات التي حصلنا عليها ,
                وكيف سنستخدمها والجهة التي سنقوم بتزويدها بهذه البيانات.
                <br /><br />
                <b>
                    الاتصال بنا
                </b>
                <br />
                يمكنكم الاتصال بنا عند الحاجة من خلال الضغط على رابط اتصل بنا المتوفر في روابط موقعنا
                او الارسال الى بريدنا الالكتروني info على اسم النطاق اعلاه
                <br /><br />
                <b>
                    اخيرا
                </b>
                <br />إن مخاوفك واهتمامك بشأن سرية وخصوصية البيانات تعتبر مسألة في غاية الأهمية بالنسبة لنا.
                نحن نأمل أن يتم تحقيق ذلك من خلال هذه السياسة.

                <!-- End Documents -->
                <br /><br />
            </section>
        </div>
    </div>
</div>
    @else
<div class="row gray-section no-margin">
    <div class="container">
        <div class="content-block">
            <h2>Privacy Policy</h2>
            <div class="title-divider"></div>
            <p >{!! Setting::get($page) !!}</p>

            <section id="content" >

                We appreciate your interest and your concerns about the privacy of your data on the Internet.
                <br />
                This policy has been prepared to help you understand the nature of the data that we collect from you when you visit our website and how we deal with this personal data.

                <br />
                <br />
                <b>
                    Browsing
                </b><br />
                We did not design this website to collect your personal data from your computer
                While browsing this site, only the data provided by you will be used with your knowledge and of your own free will
                <br />
                <br />
                <b>
                    Internet Protocol (IP) address
                </b>
                <br />&nbsp;
                Whenever you visit any website including this website, the host server will register the Internet Protocol address
                & nbsp; (IP)
                Your date and time of visit, the type of web browser you are using and the address
                URL
                Of any website that refers you to this website.
                <br /><b>
                    <br />
                    Network scans
                    <br /></b>

                Our online scans enable us to collect specific data
                Such as the data required of you regarding your outlook and how you feel about our site. Your responses are of the utmost importance,
                And our appreciation is that it enables us to improve the level of our site, and you have complete freedom and choice in providing
                Your name and other data.
                <br />
                <br /><b>
                    Links to other sites on the Internet
                </b>
                <br />

                Our site may include links to other sites on the Internet, or ads from other sites, such as
                <a href="https://www.google.com/adsense" target="_blank">
                    Google AdSense </a>

                We are not responsible for the methods of collecting data by these sites. You can view the privacy policies and contents of those sites that are accessed through any link within this site.
                <br />
                <br />
                We may use third-party advertising companies to serve ads when you visit our website. These companies have the right to use information about your visits to this and other websites
                (Except for the name, address, email address or phone number) in order to provide advertisements about the goods and services of interest to you. If you would like more information about this as well as if you want to know the choice available to you to prevent the use of this information by these companies
                ،
                <a href="http://www.google.com/privacy_ads.html" target="_blank">
                    Please click here. </a>
                <br />
                <br /><b>
                    Disclosure of information
                    <br /></b>
                We will at all times maintain the privacy and confidentiality of all personal data that we obtain. This information will not be disclosed unless it is required under any law or when we believe in good faith that such a procedure will be required or desirable to comply with the law, or to defend or protect the property rights of this site or the beneficiaries of it.
                <br />
                <br /><b>
                    The data necessary to carry out the transactions required by you
                    <br /></b>
                Whenever we need any of your data, we will ask you to provide it of your own free will. As this information will help us in contacting you and carrying out your requests wherever possible. The data submitted by you will never be sold to any third party for the purpose of marketing it for its own benefit without obtaining your prior and written consent unless this is done on the basis that it is within the collective data used for statistical purposes and research without including any data that can be used to identify you.
                <br />
                <br /><b>
                    When contacting us
                    <br /></b>
                All data provided by you will be treated as confidential. The forms submitted directly on the network require the submission of data that will help us to improve our website. The data provided by you will be used in all of your inquiries, comments, or requests from this site or any of its sites.
                <br /><br /><b>
                    Disclosure of information to any third party
                    <br /></b>
                We will not sell, trade, lease, or divulge any information for the benefit of any third party outside this site, or its affiliate sites. The information will be disclosed only if an order is issued by any judicial or regulatory authority.
                <br /><br /><b>
                    Amendments to the information privacy and confidentiality policy
                </b><br />

                We reserve the right to amend the terms and conditions of the Privacy Policy and the Privacy of Information if necessary and when appropriate. The modifications will be implemented here or on a page
                <a href="http://www.ebmark.com/privacy.html">
                    Privacy policy
                </a>
                The principal will constantly be notified of the data we have obtained, how we will use it and who we will supply it with.
                <br /><br />
                <b>
                    Contact us
                </b>
                <br />
                You can contact us when needed by clicking on the contact us link provided in our website links or sending to our email info on the domain name above
                <br /><br />
                <b>
                    finally
                </b>
                <br />
                Your concerns and concerns about the confidentiality and privacy of the data are very important to us. We hope this will be achieved through this policy.

                <!-- End Documents -->
                <br /><br />
            </section>
        </div>
    </div>
</div>
    @endif

@endsection