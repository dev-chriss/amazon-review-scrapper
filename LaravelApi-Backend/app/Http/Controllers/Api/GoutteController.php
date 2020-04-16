<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

include_once(app_path() . '\simple_html_dom.php');

class GoutteController extends Controller
{
    public function index()
    {
        $html = file_get_html('https://www.amazon.com/product-reviews/B003BEDQL2');
        $array = array();
        
        foreach($html->find('.review') as $element) {
            $collection = array();

            foreach($element->find('.a-profile-name') as $element2) {
                $collection['author'] = $element2->plaintext;
            }

            foreach($element->find('.review-title') as $element2) {
                $collection['title'] = $element2->plaintext;
            }
            foreach($element->find('a.a-profile') as $element2) {
                $collection['authorLink'] = "https://www.amazon.com/" . $element2->href;
            }
                
            foreach($element->find('.review-title') as $element2) {
                $collection['reviewLink'] = "https://www.amazon.com/" . $element2->href;
            }

            foreach($element->find('.review-rating') as $element2) {
                $collection['rating'] = Str::substr($element2->plaintext, 0, 1);
            }

            foreach($element->find('.review-date') as $element2) {
                $collection['date'] = $element2->plaintext;
            }

            foreach($element->find('span.review-comment-total.aok-hidden') as $element2) {
                $collection['commentCount'] = $element2->plaintext;
            }

            $collection['verified'] = "No";
            foreach($element->find('span.a-size-mini.a-color-state.a-text-bold') as $element2) {
                if($element2->plaintext == 'Verified Purchase') $collection['verified'] = "Yes";
            }

            $collection['pictureIncluded'] = "No";
            foreach($element->find('div.review-image-tile-section') as $element2) {
                if($element2) $collection['pictureIncluded'] = "Yes";
            }

            $array[] = $collection;
        }

        // echo $array[0]->verified;
        // print_r($array);
        // foreach($array as $element) {
        //     echo $element->author . "\n";
        //     echo $element->title . "\n";
        //     echo $element->authorLink . "\n";
        //     echo $element->reviewLink . "\n";
        //     echo $element->rating . "\n";
        //     echo $element->date . "\n";
        //     echo $element->commentCount . "\n";
        //     echo $element->verified . "\n";
        //     if(Arr::exists($element, 'pictureIncluded'))  echo "pictureIncluded:" . $element->pictureIncluded . "\n";
        //     echo "\n";
        // }

        return response()->json([
            'reviews' => $array
        ], 200);
    }

    public function show($id)
    {
        if (!$id) {
           throw new HttpException(400, "Invalid id");
        }

        $html = file_get_html('https://www.amazon.com/product-reviews/' . $id);
        $array = array();
        
        foreach($html->find('.review') as $element) {
            $collection = array();

            foreach($element->find('.a-profile-name') as $element2) {
                $collection['author'] = $element2->plaintext;
            }

            
            foreach($element->find('a.a-profile') as $element2) {
                $collection['authorLink'] = "https://www.amazon.com/" . $element2->href;
            }
                
            foreach($element->find('.review-title') as $element2) {
                $collection['reviewLink'] = "https://www.amazon.com/" . $element2->href;
            }

            foreach($element->find('.review-rating') as $element2) {
                $collection['rating'] = Str::substr($element2->plaintext, 0, 1);
            }

            foreach($element->find('.review-date') as $element2) {
                $collection['date'] = $element2->plaintext;
            }

            foreach($element->find('span.review-comment-total.aok-hidden') as $element2) {
                $collection['commentCount'] = $element2->plaintext;
            }

            $collection['verified'] = "No";
            foreach($element->find('span.a-size-mini.a-color-state.a-text-bold') as $element2) {
                if($element2->plaintext == 'Verified Purchase') $collection['verified'] = "Yes";
            }

            $collection['pictureIncluded'] = "No";
            foreach($element->find('div.review-image-tile-section') as $element2) {
                if($element2) $collection['pictureIncluded'] = "Yes";
            }

            foreach($element->find('.review-title') as $element2) {
                $collection['title'] = trim($element2->plaintext, " ");
            }

            foreach($element->find('span.a-size-base.review-text.review-text-content') as $element2) {
                $collection['body'] = trim($element2->plaintext, " ");
            }

            $array[] = $collection;
        }

        return response()->json([
            'reviews' => $array
        ], 200);
    }

}