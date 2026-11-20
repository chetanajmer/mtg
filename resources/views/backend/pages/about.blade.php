@extends('layouts.app')

@section('content')
@php
  $c = $content;
  $v = function ($key, $default) { return old($key, $default); };
  $heroImage = !empty($c['hero_image']) && is_file(public_path('upload/pages/' . $c['hero_image'])) ? asset('upload/pages/' . rawurlencode($c['hero_image'])) : null;
@endphp
<style>
  .ab-edit .card-box h5 { font-weight: 700; margin: 0 0 4px; }
  .ab-edit .ab-hint { color: #6b7280; font-size: 12px; margin: 0 0 14px; }
  .ab-edit label { font-weight: 600; }
  .ab-edit .ab-pair { border: 1px solid #eef0f3; border-radius: 6px; padding: 12px 12px 2px; margin-bottom: 12px; background: #fafbfc; }
  .ab-edit .ab-step-name { font-weight: 700; color: #15171a; }
  .ab-edit .ab-hero-preview { max-width: 280px; border-radius: 6px; border: 1px solid #e5e7eb; display: block; margin-bottom: 10px; }
  .ab-edit .ab-actions { position: sticky; bottom: 0; background: #fff; border-top: 1px solid #e5e7eb; padding: 12px 0; margin-top: 10px; z-index: 5; }
</style>

<div class="content-page">
  <div class="content">
    <div class="container ab-edit">
      <div class="row">
        <div class="col-sm-12">
          <h4 class="page-title">About Us page</h4>
          <p class="ab-hint">This is the text of the public <a href="{{ url('aboutus') }}" target="_blank" rel="noopener">About Us page</a>. Categories, step names and contact details are filled in automatically.</p>
        </div>
      </div>

      @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
      @if($errors->any())<div class="alert alert-danger">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>@endif

      <form action="{{ route('updateaboutus') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
          <div class="col-lg-7">
            <div class="card-box">
              <h5>Top of the page</h5>
              <p class="ab-hint">The first thing visitors see.</p>
              <div class="form-group"><label for="hero_eyebrow">Small label above the title</label>
                <input type="text" id="hero_eyebrow" name="hero_eyebrow" class="form-control" maxlength="60" value="{{ $v('hero_eyebrow', $c['hero_eyebrow']) }}"></div>
              <div class="form-group"><label for="hero_title">Title <span class="text-danger">*</span></label>
                <input type="text" id="hero_title" name="hero_title" class="form-control" maxlength="150" required value="{{ $v('hero_title', $c['hero_title']) }}"></div>
              <div class="form-group"><label for="hero_text">Text <span class="text-danger">*</span></label>
                <textarea id="hero_text" name="hero_text" class="form-control" rows="4" maxlength="1000" required>{{ $v('hero_text', $c['hero_text']) }}</textarea></div>
            </div>
          </div>
          <div class="col-lg-5">
            <div class="card-box">
              <h5>Image (optional)</h5>
              <p class="ab-hint">Shown next to the title on large screens and under it on phones. JPG, PNG or WEBP, up to 4 MB; a wide photo works best.</p>
              @if($heroImage)
                <img src="{{ $heroImage }}" alt="Current image" class="ab-hero-preview">
                <div class="checkbox"><label><input type="checkbox" name="remove_image" value="1"> Remove this image</label></div>
              @else
                <p class="ab-hint">No image: the title uses the full width.</p>
              @endif
              <input type="file" name="hero_image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
            </div>
          </div>
        </div>

        <div class="card-box">
          <h5>What we do</h5>
          <div class="row">
            <div class="col-md-4"><div class="form-group"><label for="what_title">Section title <span class="text-danger">*</span></label>
              <input type="text" id="what_title" name="what_title" class="form-control" maxlength="100" required value="{{ $v('what_title', $c['what_title']) }}"></div></div>
            <div class="col-md-8"><div class="form-group"><label for="what_intro">Line under the title</label>
              <input type="text" id="what_intro" name="what_intro" class="form-control" maxlength="300" value="{{ $v('what_intro', $c['what_intro']) }}"></div></div>
          </div>
          <p class="ab-hint">Four cards. Write <code>:categories</code> to show the current number of categories.</p>
          <div class="row">
            @foreach($c['cards'] as $i => $card)
              <div class="col-md-6"><div class="ab-pair">
                <div class="form-group"><label>Card {{ $i + 1 }} title <span class="text-danger">*</span></label>
                  <input type="text" name="cards[{{ $i }}][title]" class="form-control" maxlength="80" required value="{{ $v("cards.$i.title", $card['title']) }}"></div>
                <div class="form-group"><label>Card {{ $i + 1 }} text <span class="text-danger">*</span></label>
                  <textarea name="cards[{{ $i }}][text]" class="form-control" rows="3" maxlength="300" required>{{ $v("cards.$i.text", $card['text']) }}</textarea></div>
              </div></div>
            @endforeach
          </div>
        </div>

        <div class="card-box">
          <h5>How an order works</h5>
          <div class="row">
            <div class="col-md-4"><div class="form-group"><label for="steps_title">Section title <span class="text-danger">*</span></label>
              <input type="text" id="steps_title" name="steps_title" class="form-control" maxlength="100" required value="{{ $v('steps_title', $c['steps_title']) }}"></div></div>
            <div class="col-md-8"><div class="form-group"><label for="steps_intro">Line under the title</label>
              <input type="text" id="steps_intro" name="steps_intro" class="form-control" maxlength="300" value="{{ $v('steps_intro', $c['steps_intro']) }}"></div></div>
          </div>
          <p class="ab-hint">The step names come from the order workflow and can't be changed here; write one short line for each.</p>
          <div class="row">
            @foreach($c['steps'] as $n => $text)
              <div class="col-md-6"><div class="form-group">
                <label>{{ $n }}. <span class="ab-step-name">{{ $steps[$n]['customer'] ?? '' }}</span> <span class="text-danger">*</span></label>
                <textarea name="steps[{{ $n }}]" class="form-control" rows="2" maxlength="300" required>{{ $v("steps.$n", $text) }}</textarea>
              </div></div>
            @endforeach
          </div>
        </div>

        <div class="card-box">
          <h5>What you can order</h5>
          <p class="ab-hint">The category tiles below this heading come from the catalogue automatically.</p>
          <div class="row">
            <div class="col-md-4"><div class="form-group"><label for="categories_title">Section title <span class="text-danger">*</span></label>
              <input type="text" id="categories_title" name="categories_title" class="form-control" maxlength="100" required value="{{ $v('categories_title', $c['categories_title']) }}"></div></div>
            <div class="col-md-8"><div class="form-group"><label for="categories_intro">Line under the title</label>
              <input type="text" id="categories_intro" name="categories_intro" class="form-control" maxlength="300" value="{{ $v('categories_intro', $c['categories_intro']) }}"></div></div>
          </div>
        </div>

        <div class="card-box">
          <h5>Why companies work with us</h5>
          <div class="row">
            <div class="col-md-4"><div class="form-group"><label for="why_title">Section title <span class="text-danger">*</span></label>
              <input type="text" id="why_title" name="why_title" class="form-control" maxlength="100" required value="{{ $v('why_title', $c['why_title']) }}"></div></div>
            <div class="col-md-8"><div class="form-group"><label for="why_intro">Line under the title</label>
              <input type="text" id="why_intro" name="why_intro" class="form-control" maxlength="300" value="{{ $v('why_intro', $c['why_intro']) }}"></div></div>
          </div>
          <p class="ab-hint">Six points: a short bold start, then the rest of the sentence.</p>
          <div class="row">
            @foreach($c['points'] as $i => $point)
              <div class="col-md-6"><div class="ab-pair">
                <div class="form-group"><label>Point {{ $i + 1 }}, bold part <span class="text-danger">*</span></label>
                  <input type="text" name="points[{{ $i }}][title]" class="form-control" maxlength="80" required value="{{ $v("points.$i.title", $point['title']) }}"></div>
                <div class="form-group"><label>Point {{ $i + 1 }}, rest <span class="text-danger">*</span></label>
                  <input type="text" name="points[{{ $i }}][text]" class="form-control" maxlength="300" required value="{{ $v("points.$i.text", $point['text']) }}"></div>
              </div></div>
            @endforeach
          </div>
        </div>

        <div class="card-box">
          <h5>Closing band</h5>
          <p class="ab-hint">The dark box at the bottom. Phone and email are added automatically from the company settings.</p>
          <div class="row">
            <div class="col-md-6"><div class="form-group"><label for="cta_title">Title <span class="text-danger">*</span></label>
              <input type="text" id="cta_title" name="cta_title" class="form-control" maxlength="150" required value="{{ $v('cta_title', $c['cta_title']) }}"></div></div>
            <div class="col-md-6"><div class="form-group"><label for="cta_text">Text</label>
              <input type="text" id="cta_text" name="cta_text" class="form-control" maxlength="300" value="{{ $v('cta_text', $c['cta_text']) }}"></div></div>
          </div>
        </div>

        <div class="ab-actions">
          <button type="submit" class="btn btn-primary">Save changes</button>
          <a href="{{ url('aboutus') }}" target="_blank" rel="noopener" class="btn btn-default">View the page</a>
          <button type="submit" name="reset" value="1" class="btn btn-link pull-right" formnovalidate onclick="return confirm('Put back the original text and remove the image?');">Reset to default text</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
