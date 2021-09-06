<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{
    private function addClassToFirstPtag($blogContent, $findWhat = '', $classToAdd = ''){
        /* Format blog content before print */
        $replacedBlogContent = $blogContent;

        $dom = new \DOMDocument();
        @$dom->loadHTML($blogContent);
        $xpath = new \DOMXPath($dom);

        if($findWhat == 'p'){
            // Find first <p> tag
            $nodes = $xpath->query('//p[position()=1]');
        }elseif ($findWhat == 'div'){
            $nodes = $xpath->query('//div[position()=1]');
        }

        if($nodes->length > 0){
            $node = $nodes[0];

//            $addClass = 'title-description-post';
            $addClass = $classToAdd;
            if ($node->hasAttribute('class')) {
                $classes = preg_split('/\s+/', $node->getAttribute('class'));
            } else {
                $classes = [];
            }
            $classes[] = $addClass;
            $classes = array_unique($classes);
            $node->setAttribute('class', implode(' ', $classes));
            // remove some html tags
            $replacedBlogContent = str_replace('<html><body>','', $dom->saveHTML() );
            $replacedBlogContent = str_replace('</body></html>','', $replacedBlogContent );
            $replacedBlogContent = str_replace('<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">','', $replacedBlogContent );
            $replacedBlogContent = str_replace('<meta charset="utf-8" />','', $replacedBlogContent );
        }
        return $replacedBlogContent;
    }

    public function view($slug) {
        $query = DB::table('blogs')->where('slug', $slug)->where('status', 'published')->get();
        if (!empty($query[0])) {
            $data['blog'] = $query[0];

            /* Add class to fist <p> and first <div> of Blog content */
            $blogContent = $data['blog']->content;
            $blogContent = $this->addClassToFirstPtag($blogContent, 'p', 'title-description-post');
            $blogContent = $this->addClassToFirstPtag($blogContent, 'div', 'entry-content');

            $data['blog']->content = $blogContent;
            /* End */

            $data['seoConfig']['title'] = $data['blog']->title;
            $data['seoConfig']['desc'] = $data['blog']->meta_description;
            $data['seoConfig']['keyword'] = $data['blog']->meta_keywords;
            return view('blogs.blog')->with($data);
        } else {
            /* return 404 */
            return response(view('errors.404'), 404);
        }
    }

    public function viewAmp($slug) {
        $query = DB::table('blogs')->where('slug', $slug)->where('status', 'published')->get();
        if (!empty($query[0])) {
            $data['blog'] = $query[0];
            $data['seoConfig']['title'] = $data['blog']->title;
            $data['seoConfig']['desc'] = $data['blog']->meta_description;
            $data['seoConfig']['keyword'] = $data['blog']->meta_keywords;
            return view('amp-version.blogs.blog')->with($data);
        } else {
            /* return 404 */
            return response(view('errors.404'), 404);
        }
    }

    public function allData() {
        $data['seoConfig']['title'] = 'Tips & Blogs About Online Shopping - Highly Recommendation';
        $data['seoConfig']['desc'] = 'Best and useful Tips & Blogs for Online Shopping. Help you make better purchasing decisions.';
        $data['seoConfig']['keyword'] = 'Shopping tips, How to using coupon code';

        $data['blogs'] = DB::table('blogs')->select('id', 'slug', 'title', 'content', 'thumbnail')->where('status', 'published')->orderBy('id', 'DESC')->get();
        if (!empty($data['blogs'])) {
            foreach ($data['blogs'] as $k => $b) {
                $b->timeToRead = ceil(str_word_count($b->content) / 200);
                $data['blogs'][$k] = $b;
            }
        }
        return $data;
    }

    public function allAmp() {
        $data = $this->allData();
        return view('amp-version.blogs.index')->with($data);
    }

    public function all() {
        $data = $this->allData();
        return view('blogs.all')->with($data);
    }
}
