@extends('shop.layouts.app')

@section('content')

<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('home')}}">Home</a></li>
                <li class="breadcrumb-item active">{{ $page->title}}</li>
            </ol>
        </div>
    </div>
</section>
@if ($page->slug == 'contact-us' )

    

    <section class=" section-10">
        <div class="container">
            <div class="section-title mt-5 ">
                <h2>{{ $page->title }}</h2>
            </div>   
        </div>
    </section>

    <section>
        <div class="container">          
            <div class="row">
                <div class="col-md-6 mt-3 pe-lg-5">
                    <p>
                        {!! $page->content !!}
                    </p>        
                </div>

                <div class="col-md-6">
                    <form id="contactForm" name="contact-form" method="post">
                        <div class="mb-3">
                            <label class="mb-2" for="name">Name</label>
                            <input class="form-control" id="name" type="text" name="name" required>
                            <small class="text-danger error-name"></small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="mb-2" for="email">Email</label>
                            <input class="form-control" id="email" type="email" name="email" required>
                            <small class="text-danger error-email"></small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="mb-2">Subject</label>
                            <input class="form-control" id="subject" type="text" name="subject" required>
                            <small class="text-danger error-subject"></small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="mb-2" for="message">Message</label>
                            <textarea class="form-control" rows="3" id="message" name="message"></textarea>
                            <small class="text-danger error-message"></small>
                        </div>
                      
                        <div class="form-submit">
                            <button class="btn btn-dark" type="button" id="form-submit"><i class="mdi mdi-message-outline"></i> Send Message</button>
                            <div id="form-success" class="text-success mt-3" style="display: none;">Message sent successfully!</div>
                        </div>
                    </form>
                </div>
                
            </div>
        </div>
    </section>

@else
<section class=" section-10">
    <div class="container">
        <div class="section-title mt-5 ">
            <h2>{{ $page->title }}</h2>
        </div>
        <p>
            {!! $page->content !!}
        </p>
        
    </div>
</section>    
@endif


@endsection
@section('customScript')
<script>
    $(document).ready(function () {
        $('#form-submit').on('click', function (e) {
            e.preventDefault();

            // Clear previous errors
            $('.text-danger').text('');
            $('#form-success').hide();

            // Collect form data
            let formData = {
                name: $('#name').val(),
                email: $('#email').val(),
                subject: $('#subject').val(),
                message: $('#message').val(),
            };

            // AJAX request
            $.ajax({
                url: '{{ route("contact.submit")}}', // Replace with your route URL
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Add CSRF token if needed
                },
                success: function (response) {
                    if (response.status === true) {
                        $('#form-success').text(response.message).show();
                        $('#contactForm')[0].reset(); // Clear the form
                    } else {
                        alert(response.message || 'Something went wrong. Please try again.');
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        if (errors.name) {
                            $('.error-name').text(errors.name[0]);
                        }
                        if (errors.email) {
                            $('.error-email').text(errors.email[0]);
                        }
                        if (errors.subject) {
                            $('.error-subject').text(errors.subject[0]);
                        }
                        if (errors.message) {
                            $('.error-message').text(errors.message[0]);
                        }
                    } else {
                        alert('Failed to send the message. Please try again later.');
                    }
                }
            });
        });
    });
</script>

@endsection