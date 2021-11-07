@extends('frontend.layouts.app')

@section('title', 'Contact' . ' |')

@section('content')
    <section class="py-5" id="contact">
        <div class="container details-body">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="text-center mb-3">
                        <h2 class="text-uppercase mb-4">Get In Touch </h2>
                        <div class="single-line"></div>
                    </div>
                </div>
            </div>


            <div class="row mt-5">
                <aside class="col-md-6">

                    <form>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input name="name" id="name" type="text" class="form-control" placeholder="Enter your name..">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email address</label>
                                    <input name="email" id="email" type="email" class="form-control" placeholder="Enter your email..">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="subject">Subject</label>
                                    <input type="text" class="form-control" id="subject" placeholder="Enter Subject.." />
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="message">Message</label>
                                    <textarea rows="3" class="form-control" placeholder="Enter message.."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary">Send Message</button>
                            </div>
                        </div>
                    </form>

                </aside>


                <div class="col-md-6 ">

                    <div class="contact-address">
                        <h4 class="title mb-4">Contact Info</h4>
                        <p class="text-muted">Write to us at bu@edu.bd or fill in the form.</p>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="contact-address">
                                <h5 class="title">Address - A</h5>
                                <p class="text-muted">15/1, Iqbal Road, Mohammadpur, Dhaka – 1207</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="contact-address">
                                <h5 class="title">Address - B</h5>
                                <p class="text-muted">5/B, Beribandh Main Road, Adabar, Mohammadpur, Dhaka</p>
                            </div>
                        </div>
                    </div>


                </div>
            </div>


        </div>
    </section>

@endsection
