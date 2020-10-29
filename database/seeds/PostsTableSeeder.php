<?php

use App\Category;
use App\Post;
use App\Tag;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $author1 = User::create([
            'name' => 'Ali Zain',
            'email' => 'alizain@laravel.com',
            'password' => Hash::make('passwword')
        ]);

        $author2 = User::create([
            'name' => 'NK',
            'email' => 'NK@laravel.com',
            'password' => Hash::make('passwword')
        ]);

        $category1 = Category::create([
            'name' => 'News'
        ]);
        $category2 = Category::create([
            'name' => 'Marketing'
        ]);
        $category3 = Category::create([
            'name' => 'Partnership'
        ]);
        $category4 = Category::create([
            'name' => 'Design'
        ]);


        $post1 = Post::create([
            'title' => 'We relocated our office to a new designed garage',
            'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
            'content' => 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old.',
            'category_id' => $category1->id,
            'image' => 'posts/6.jpg',
            'user_id' => $author1->id,
            'published_at' => Carbon::now(),
        ]);

        $post2 = $author2->posts()->create([
            'title' => 'Top 5 brilliant content marketing strategies',
            'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
            'content' => 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old.',
            'category_id' => $category2->id,
            'image' => 'posts/7.jpg',
            'published_at' => Carbon::now(),
        ]);

        $post3 =  $author1->posts()->create([
            'title' => 'Best practices for minimalist design with example',
            'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
            'content' => 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old.',
            'category_id' => $category3->id,
            'image' => 'posts/8.jpg',
            'published_at' => Carbon::now(),
        ]);

        $post4 = $author2->posts()->create([
            'title' => 'Congratulate and thank to Maryam for joining our team',
            'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
            'content' => 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old.',
            'category_id' => $category2->id,
            'image' => 'posts/9.jpg',
            'published_at' => Carbon::now(),
        ]);


        $tag1 = Tag::create([
            'name' => 'Job'
        ]);

        $tag2 = Tag::create([
            'name' => 'Customers'
        ]);

        $tag3 = Tag::create([
            'name' => 'Records'
        ]);

        $tag4 = Tag::create([
            'name' => 'Complaints and Suggestions'
        ]);

        $post1->tags()->attach([ $tag1->id, $tag2->id ]);
        $post2->tags()->attach([$tag2->id, $tag3->id]);
        $post3->tags()->attach([$tag2->id, $tag1->id]);
        $post4->tags()->attach([$tag1->id, $tag3->id]);
    }
}
