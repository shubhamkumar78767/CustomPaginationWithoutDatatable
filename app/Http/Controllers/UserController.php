<?php

namespace App\Http\Controllers;

use App\Jobs\SeedDatabase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

class UserController extends Controller
{
    public function index(Request $req)
    {
        $page = $req->input('page') ?? 1;
        $limit = 10; // Set the number of records per page
        $offset = ($page - 1) * $limit;

        $users = DB::table('users')
        ->offset($offset)
        ->paginate($limit);

        // Fetch the records for the current page
        $records = User::paginate($limit);

        // Get the current page number
        $currentPage = $records->currentPage();

        // Get the last page number
        $lastPage = $records->lastPage();

        // Get the first and last page url
        $firstPageUrl = $users->url(1);
        $lastPageUrl = $users->url($lastPage);

        // Set the number of pages to show at the start and end of the pagination links
        $startPages = 2;
        $endPages = 2;

        // Calculate the start and end page numbers for the pagination links
        $startPage = max($currentPage - $startPages, 1);
        $endPage = min($currentPage + $endPages, $lastPage);

        // Build the pagination links HTML
        $html = '<nav aria-label="Page navigation example"><ul class="pagination">';

        // Add the "Previous" link
        if ($records->onFirstPage()) {

            $html .= '<li class="page-item disabled"><span class="page-link">Prev</span></li>';
        } else {
            $html .= '<li class="page-item"><a class="page-link" href="' . $firstPageUrl . '" rel="first">First</a></li>';
            $html .= '<li class="page-item"><a class="page-link" href="' . $records->previousPageUrl() . '" rel="prev">Prev</a></li>';
        }

        // Add the page links
        for ($i = $startPage; $i <= $endPage; $i++) {
            if ($i == $currentPage) {
                $html .= '<li class="page-item active d-inline-block"><span class="page-link">' . $i . '</span></li>';
            } else {
                $html .= '<li class="page-item d-inline-block"><a class="page-link" href="' . $records->url($i) . '">' . $i . '</a></li>';
            }
        }

        // Add the "Next" link
        if ($records->hasMorePages()) {
            $html .= '<li class="page-item d-inline-block"><a class="page-link" href="' . $records->nextPageUrl() . '" rel="next">Next</a></li>';
            $html .= '<li class="page-item d-inline-block"><a class="page-link" href="' . $lastPageUrl . '" rel="last">Last</a></li>';
        } else {
            $html .= '<li class="page-item disabled d-inline-block"><span class="page-link">Next</span></li>';
        }

        $html .= '</ul></nav>';

        
        return view('listing', compact('users','html'));
    }

    public function SeedDatabase(){
        SeedDatabase::dispatch();
    }
}
