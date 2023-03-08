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
        $where = $req->input('where') ?? [
            ['id', '>', 0],
            // ['created_at', '>', '2022-01-01']
        ];

        $page = $req->input('page') ?? 1;
        $search = $req->input('search') ?? '';
        $limit = $req->input('limit') ?? 10; // Set the number of records per page
        $offset = ($page - 1) * $limit;

        $users = DB::table('users')
            ->where($where)
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->orWhere('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            })

        ->offset($offset)
        ->paginate($limit);
        
        // append url parameter with pagination links
        $users->appends(['search' => $search]);
        $users->appends(['limit' => $limit]);

        // Fetch the records for the current page
        $currentPage = $users->currentPage();

        // Get the last page number
        $lastPage = $users->lastPage();
        // echo $lastPage; die;

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
        if ($users->onFirstPage()) {

            $html .= '<li class="page-item disabled"><span class="page-link">Prev</span></li>';
        } else {
            $html .= '<li class="page-item"><a class="page-link" href="' . $firstPageUrl . '" rel="first">First</a></li>';
            $html .= '<li class="page-item"><a class="page-link" href="' . $users->previousPageUrl() . '" rel="prev">Prev</a></li>';
        }

        // Add the page links
        for ($i = $startPage; $i <= $endPage; $i++) {
            if ($i == $currentPage) {
                $html .= '<li class="page-item active d-inline-block"><span class="page-link">' . $i . '</span></li>';
            } else {
                $html .= '<li class="page-item d-inline-block"><a class="page-link" href="' . $users->url($i) . '">' . $i . '</a></li>';
            }
        }

        // Add the "Next" link
        if ($users->hasMorePages()) {
            $html .= '<li class="page-item d-inline-block"><a class="page-link" href="' . $users->nextPageUrl() . '" rel="next">Next</a></li>';
            $html .= '<li class="page-item d-inline-block"><a class="page-link" href="' . $lastPageUrl . '" rel="last">Last</a></li>';
        } else {
            $html .= '<li class="page-item disabled d-inline-block"><span class="page-link">Next</span></li>';
        }

        $html .= '</ul></nav>';


        return view('listing', compact('users', 'html'));
    }

    public function SeedDatabase()
    {
        SeedDatabase::dispatch();
    }
}
