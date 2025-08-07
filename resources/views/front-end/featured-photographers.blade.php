@extends('front-end.layout.main')
@section('styles')
<style>

        .featured-photographers {
            background: #000;
        }
        .featured-photographers .row {
            align-items: center;
        }
        .table-sc-new {
            background: #000;
            color: #fff;
            padding: 90px 0;
        }
        .apply-sc {
            background: #000;
            text-align: center;
        }
        .textwidget a{
            color: #ffc205;
        }
        .apply-btn a {
            background: #16A085;
            color: #ffffff;
            border: 1px solid #16A085;
            padding: 8px 16px;
            font-size: 18px;
            display: inline-block;
            transition: all .3s ease-in-out;
            border-radius: 3px;
            width: 100%;
            max-width: 200px;
            margin-top: 20px;
        }
        .apply-sc .head-box.custom-wrapper {
            padding: 40px;
        }
        .photographer-list {
                    width: 100%;
                    border-collapse: collapse;
                    font-size: 14px;
        }
        .photographer-list th, .photographer-list td {
            padding: 15px;
            border-bottom: 1px solid #444242;
            vertical-align: middle;
        }
        .featured-photographers {
            padding-top: 90px;
        }
        .toggle-cell a {
            color: #ffc205;
        }
        .apply-sc-hear {
            background: #000;
            padding: 90px 0;
        }
        .apply-sc-hear p {
            text-align: center;
            color: #fff;
        }
            .photographer-list th {
            background-color: #424242;
            font-weight: 600;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .photographer-row {
            display: table-row;
        }
        .photographer-row.hidden {
            display: none;
        }
        .toggle-cell {
            text-align: right;
            padding-right: 0;
        }
       .toggle-icon {
            cursor: pointer;
            font-size: 22px;
            color: #000;
            user-select: none;
            margin-left: 15px;
            vertical-align: middle;
            background: #ffc205;
            width: 20px;
            height: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
        .website a {
            text-decoration: underline;
            color: #2980b9;
            white-space: nowrap;
            transition: color 0.3s ease;
        }
        .website a:hover {
            color: #1a5276;
        }
        /* Column Widths and Alignment */
        .photographer-list th:nth-child(1),
        .photographer-list td:nth-child(1) {
            width: 35%;
            text-align: left;
        }
        .photographer-list th:nth-child(2),
        .photographer-list td:nth-child(2) {
            width: 20%;
            text-align: left;
        }
        .photographer-list th:nth-child(3),
        .photographer-list td:nth-child(3) {
            width: 25%;
            text-align: left;
        }
        .photographer-list th:nth-child(4),
        .photographer-list td:nth-child(4) {
            width: 20%;
            text-align: right;
        }
        .apply-btn a:hover {
            background: #000;
            border: 1px solid #000;
        }
        /* Responsive Design */
        @media (max-width: 768px) {
            .photographer-list th,
            .photographer-list td {
                display: block;
                width: 100%;
                padding: 12px;
                text-align: left;
            }
            .photographer-list tr {
                display: block;
                margin-bottom: 15px;
                border: 1px solid #e0e0e0;
                border-radius: 5px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            }
            .photographer-row.hidden {
                display: none;
            }
            .toggle-cell {
                text-align: right;
                padding-right: 10px;
            }
            .photographer-list th:nth-child(n),
            .photographer-list td:nth-child(n) {
                width: 100%;
            }
        }
</style>

@endsection
@section('content')
<section class="featured-photographers">
    <div class="container ">

        <div class="row">
                <!-- Add row here -->

                <div class="col-lg-6">
                    <div class="entry-text">
                        <div class="widget-title aos-init aos-animate" data-aos="fade-left">
                            <h3>Meet the Professionals Behind Our Incredible Imagery</h3>
                            <div class="textwidget">
                                <p style="margin-bottom: 10px;">At <strong><a href="{{url('/')}}">Shadows Photo Printing</a></strong>, we’re proud to collaborate with a talented community of professional photographers whose stunning work brings our products to life. Their images, captured with passion and precision, are showcased on our uniquely handcrafted, ready-to-hang canvas and photo prints—made right here in Australia. </p>

                                <p style="margin-bottom: 10px;">If you’ve fallen in love with the photos featured on our products, there’s a good chance you’re admiring the work of one of the amazing photographers listed below. </p>

                                <p style="margin-bottom: 10px;">A heartfelt <strong>thank you</strong> to our incredible pros—we couldn’t do it without you! </p>

                            </div>
                        </div>
                    </div>
                </div>
				 <div class="col-lg-6">
                    <div class="entry-img-n">
                        <figure data-aos="fade-right" class="aos-init aos-animate">
                            <img src="{{asset('assets/images/onemeet.jpg')}}" alt="Shadows Photo Printing Photographers">
                        </figure>
                    </div>
                </div>
            </div>
    </div>
</section>
<section class="table-sc-new">
	<div class="container">
		<div class="table-owner">
			<table class="photographer-list">
				<thead>
					<tr>
						<th>Name</th>
						<th>Location</th>
						<th>Category</th>
						<th></th> <!-- Empty th for toggle icon alignment -->
					</tr>
				</thead>
				<tbody>

                    @foreach($ambassadors as $ambassador)
                     <tr class="photographer-row" data-expanded="false">
						<td>{{$ambassador->name}}</td>
						<td>{{$ambassador->location}}</td>
                        @php
                            $specialty =  is_string($ambassador->specialty) ? explode(',', $ambassador->specialty) : [];
                            $items = [];
                            foreach ($specialty ?? [] as $item){
                                $items[] = $specialtyMap[$item];
                            }
                        @endphp
						<td>
                        {{implode(', ',$items)}}
                        </td>
						<td class="toggle-cell"><a href="{{$ambassador->website}}" target="_blank" class="website">{{$ambassador->website}}</a><span class="toggle-icon">+</span></td>
					</tr>
					<tr class="photographer-row hidden" data-index="0">
						<td colspan="4">Additional details for Mathew Riley Photography can be added here.</td>
					</tr>
                    @endforeach
				</tbody>
			</table>
		</div>
    </div>
</section>
<section class="apply-sc">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="head-box custom-wrapper">
					<h2 class="hdd">Interested in becoming an <br> Shadows Photographer Brand Ambassador?</h2>
					<div class="apply-btn">
						<a href="{{route('apply-form')}}">Apply Here</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="apply-sc-hear">
	<div class="container">
		<div class="row">
			<div class="col-12">
               <p>Have you noticed the stunning imagery featured on our unique, handcrafted, ready-to-hang canvas and photo prints—proudly made right here in Australia? Chances are, it’s the work of one of our incredibly talented photography partners. </p>

                <p >At <strong>Shadows Photo Printing</strong>, we’re proud to collaborate with a diverse group of professional photographers who bring our products to life. From authentic lifestyle photographers like [Photographer Name], to awe-inspiring landscape artists such as [Photographer Name], and romantic wedding storytellers like [Photographer Name], we’re grateful to work with creatives who truly elevate what we do.  </p>

                <p>Each image tells a story—and through our high-quality prints, that story becomes a lasting piece of art for your space. </p>
			</div>
		</div>
	</div>
</section>
@endsection
@section('scripts')
<script>
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('.photographer-row');
            rows.forEach(row => {
                if (!row.querySelector('.toggle-icon')) return;
                row.querySelector('.toggle-icon').addEventListener('click', function() {
                    const index = row.getAttribute('data-index');
                    const nextRow = row.nextElementSibling;
                    const isExpanded = row.getAttribute('data-expanded') === 'true';

                    if (isExpanded) {
                        nextRow.classList.add('hidden');
                        row.querySelector('.toggle-icon').textContent = '+';
                        row.setAttribute('data-expanded', 'false');
                    } else {
                        nextRow.classList.remove('hidden');
                        row.querySelector('.toggle-icon').textContent = '−';
                        row.setAttribute('data-expanded', 'true');
                    }
                });
            });
        });
    </script>
@endsection

