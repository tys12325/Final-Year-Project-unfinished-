@extends('layout.app')

@section('title', 'FAQ Support')
@section('head')
@vite(['resources/css/feedback/FAQ.css'])


@endsection

@section('content')
<a href="{{ route('feedBackSupport') }}" id="backButton"><i class="fas fa-chevron-left fa-2x"></i></a>
<div class="full-width-section">
    <div class="container">
        <section class="py-60">
            <div class="container text-control-1">
                <h2>FAQ about Graduated Xplore</h2>
                <div class="faqs-section">
                    <div class="faq accordion">
                        <div class="question-wrapper">
                            <div class="d-flex align-items-center"><span class="q-mark d-block">Q.</span>
                                <p class="question" title="">
                                    1) How does the Graduated Xplore system help me choose a university?</p>
                            </div><i class="fa-solid fa-chevron-down drop"></i>

                        </div>
                        <div class="answer-wrapper">
                            <p class="answer">Graduated Xplore system provides a personalized university selection 
                                experience based on your academic performance, 
                                budget, preferred location, and course interests. 
                                It compares universities using rankings, tuition fees, student reviews, and facilities to help you make an informed decision.</p>
                        </div>
                    </div>
                    <div class="faq accordion">
                        <div class="question-wrapper">
                            <div class="d-flex align-items-center"><span class="q-mark d-block">Q.</span>
                                <p class="question" title="">
                                    2) What factors should I consider when choosing a university?</p>
                            </div><i class="fa-solid fa-chevron-down drop"></i>

                        </div>
                        <div class="answer-wrapper">
                            <p class="answer">
                                    You should 
                                    ensure the university offers your desired course,
                                     check if the university is recognized by the education ministry, 
                                    evaluate your budget and available financial aid, 
                                    consider travel costs, accommodation, and student activities, and  
                                    research internship and job placement support.                           
                                
                            </p>
                        </div>
                    </div>
                    <div class="faq accordion">
                        <div class="question-wrapper">
                            <div class="d-flex align-items-center"><span class="q-mark d-block">Q.</span>
                                <p class="question" title=""> 3) Does Graduated Xplore provide official university rankings?</p>
                            </div><i class="fa-solid fa-chevron-down drop"></i>

                        </div>
                        <div class="answer-wrapper">
                            <p class="answer">Yes, Graduated Xplore sources university rankings from reputable 
                                organizations such as QS World University Rankings, Times Higher Education, 
                                and local government rankings. These rankings are updated regularly to reflect 
                                the latest academic performance and reputation of universities.</p>
                        </div>
                    </div>
                    <div class="faq accordion">
                        <div class="question-wrapper">
                            <div class="d-flex align-items-center"><span class="q-mark d-block">Q.</span>
                                <p class="question" title="">
                                    4) How can I compare different universities using Graduated Xplore?</p>
                            </div><i class="fa-solid fa-chevron-down drop"></i>

                        </div>
                        <div class="answer-wrapper">
                            <p class="answer">You can use the sorting/filter to evaluate universities 
                                based on key factors such as tuition fees, course offerings, 
                                scholarships, student reviews, and campus facilities. 
                                This feature allows you to make side-by-side comparisons easily.</p>
                        </div>
                    </div>
                    <div class="faq accordion">
                        <div class="question-wrapper">
                            <div class="d-flex align-items-center"><span class="q-mark d-block">Q.</span>
                                <p class="question" title="">
                                    5) Can I apply to universities directly through Graduated Xplore?</p>
                            </div><i class="fa-solid fa-chevron-down drop"></i>

                        </div>
                        <div class="answer-wrapper">
                            <p class="answer">Yes, Graduated Xplore provide a process applications directly, 
                                and also provides links and guidance for university applications. 
                                You can redirected to the university’s official admission portal 
                                to view more information.</p>
                        </div>
                    </div>
                    <div class="faq accordion">
                        <div class="question-wrapper">
                            <div class="d-flex align-items-center"><span class="q-mark d-block">Q.</span>
                                <p class="question" title="">
                                    6) How can I contact a university for more details?</p>
                            </div><i class="fa-solid fa-chevron-down drop"></i>

                        </div>
                        <div class="answer-wrapper">
                            <p class="answer">Each university profile on EduAdvisor includes 
                                contact details such as email, phone number, 
                                and official website links. You can also visit the university’s 
                                admission page for more details on programs, entry requirements, 
                                and application deadlines.</p>
                        </div>
                    </div>
                    <div class="faq accordion">
                        <div class="question-wrapper">
                            <div class="d-flex align-items-center"><span class="q-mark d-block">Q.</span>
                                <p class="question" title="">
                                    7)Is Graduated Xplore free to use?</p>
                            </div><i class="fa-solid fa-chevron-down drop"></i>

                        </div>
                        <div class="answer-wrapper">
                            <p class="answer">Yes, Graduated Xplore is completely free for students. 
                                Our goal is to provide accessible, accurate, 
                                and up-to-date university information to help 
                                students make the best choice for their future.</p>
                        </div>
                    </div>
                    <div class="faq accordion">
                        <div class="question-wrapper">
                            <div class="d-flex align-items-center"><span class="q-mark d-block">Q.</span>
                                <p class="question" title="">
                                    8)How often is the information on Graduated Xplore updated?</p>
                            </div><i class="fa-solid fa-chevron-down drop"></i>

                        </div>
                        <div class="answer-wrapper">
                            <p class="answer">Graduated Xplore is updated regularly to ensure that university rankings, 
                                tuition fees, course details, and admission requirements are accurate. 
                                We collaborate with universities and official sources to provide the latest information.</p>
                        </div>
                    </div>
                    <div class="faq accordion">
                        <div class="question-wrapper">
                            <div class="d-flex align-items-center"><span class="q-mark d-block">Q.</span>
                                <p class="question" title="">
                                    9)Is Graduated Xplore can help to track submiited application?</p>
                            </div><i class="fa-solid fa-chevron-down drop"></i>

                        </div>
                        <div class="answer-wrapper">
                            <p class="answer">Yes, Graduated Xplore can help to track the application when users submitted 
                            for their chosen university and notify users.</p>
                        </div>
                    </div>
                    <div class="faq accordion">
                        <div class="question-wrapper">
                            <div class="d-flex align-items-center"><span class="q-mark d-block">Q.</span>
                                <p class="question" title="">
                                    10)Does Graduated Xplore provide reviews from students?</p>
                            </div><i class="fa-solid fa-chevron-down drop"></i>

                        </div>
                        <div class="answer-wrapper">
                            <p class="answer">Yes, Graduated Xplore features student reviews and 
                                testimonials to give you real insights into the university experience, including campus life, facilities, and teaching quality.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

@endsection
@section('scripts')
<script>
$(document).ready(function () {
  $(".question-wrapper").click(function () {
   
    var container = $(this).closest(".accordion");
    container.toggleClass("active");

    
    var answer = container.find(".answer-wrapper");
    answer.slideToggle(300);

    
    var trigger = container.find(".drop");
    trigger.toggleClass("fa-chevron-down fa-chevron-up");
  });
});


</script>

@endsection








