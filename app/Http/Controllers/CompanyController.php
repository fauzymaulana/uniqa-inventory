<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\InvitationCategory;
use App\Models\InvitationProduct;

class CompanyController extends Controller
{
    public function index()
    {
        $heroContents = Content::where('type', 'hero')->where('is_active', true)->orderBy('order')->get();
        $bannerContents = Content::where('type', 'banner')->where('is_active', true)->orderBy('order')->get();
        $promoContents = Content::where('type', 'promo')->where('is_active', true)->orderBy('order')->get();
        $invitationCategories = InvitationCategory::with(['products' => function ($query) {
            $query->where('is_active', true);
        }])->get();

        return view('company', compact('heroContents', 'bannerContents', 'promoContents', 'invitationCategories'));
    }
}
