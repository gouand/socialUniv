/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
//import Vue from 'vue'
import store from "./store";
require('./bootstrap');
import Vue from 'vue'
window.Vue = Vue;
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */
import Vuex from 'vuex'
Vue.use(Vuex)
// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const app = new Vue({
    el: '#app'
}).$mount('#app');



$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(document).ready(function () {
    var thisElement = $('button#changeFriend')
    $(thisElement).click(function (e) {
        e.preventDefault();
        var context = $(this);
        var id = $(this).attr('userid');
        if($(this).hasClass('btn-outline-primary')) {
            $.ajax({
                method: 'POST',
                url: '/user/addFriend/' + id,
                data: {id: id}
            }).done(function (message) {
                context.addClass('btn-primary').removeClass('btn-outline-primary').text('Добавлено');
            }).fail(function (error) {
                console.log(error)
            })
        }else{
            $.ajax({
                method: 'POST',
                url: '/user/removeFriend/' + id,
                data: {id: id}
            }).done(function (message) {
                context.addClass('btn-outline-primary').removeClass('btn-primary').text('Удалено');
            }).fail(function (error) {
                console.log(error)
            })
        }


    });
    $('i.like').unbind().click(function (event) {
        event.preventDefault();
        var isLike = event.target.previousElementSibling == null
        var postId = event.target.parentNode.parentNode.dataset['postid']
        var context = $(this);
        console.log(isLike);
        $.ajax({
            method: 'POST',
            url: likePostUrl,
            data: {isLike: isLike, postId: postId}
        }).done(function (message) {
            context.addClass(isLike ? context.hasClass('far') ?  context.addClass('fas').removeClass('far').text(parseInt(context.text()) + 1): context.addClass('far').removeClass('fas').text(parseInt(context.text()) - 1)
                : context.hasClass('far') ?  context.addClass('fas').removeClass('far').text(parseInt(context.text()) + 1) : context.addClass('far').removeClass('fas').text(parseInt(context.text()) - 1) )
            // context.hasClass('far') ? context.removeClass('far').addClass('fas') : context.removeClass('fas').addClass('far')
            //

            // if(isLike){
            //     context.removeClass('fas fa-thumbs-up').addClass('far fa-thumbs-up')
            // } else
            // {
            //     context.removeClass('far fa-thumbs-down').addClass('fas fa-thumbs-down')
            // }
            if(isLike)
            {
                context.next().hasClass('fas') ? context.next().addClass('far').removeClass('fas').text(parseInt(context.text()) - 1) : context.next().addClass('far').removeClass('fas')
            }else{
                context.prev().hasClass('fas') ? context.prev().addClass('far').removeClass('fas').text(parseInt(context.text()) - 1) : context.prev().addClass('far').removeClass('fas')
            }
        }).fail(function (error) {
            console.log(error)
        })
    })


    $('.edit').unbind().click(function (e) {
        e.preventDefault();
        if($(this).hasClass('save')){
            var postId = event.target.parentNode.parentNode.dataset['postid']
            var context = $(this);
            $.ajax({
                method: 'POST',
                url: '/post/edit/' + postId,
                data: {body: context.parent().parent().find('textarea').val()}
            }).done(function (message) {
                var textVal = context.parent().parent().find('textarea').val();
                console.log(textVal)
                context.parent().parent().find('textarea').replaceWith('<p>' + textVal + '</p>')
                context.removeClass('save').text('Edit');
                console.log(message)
            }).fail(function (error) {
                console.log(error)
            })
        }else{
            $(this).parent().parent().find('p').replaceWith('<textarea name="body" value="'+ $(this).parent().parent().find('p').text() + '">' + $(this).parent().parent().find('p').text() + '</textarea>')
            $(this).addClass('save').text('Save');
        }
        //var postId = event.target.parentNode.parentNode.dataset['postid']



    });

    // $('.save').click(function () {
    //     var postId = event.target.parentNode.parentNode.dataset['postid']
    //     var context = $(this);
    //     $.ajax({
    //         method: 'POST',
    //         url: '/post/edit/' + postId,
    //         data: {body: $(this).parent().parent().find('textarea').text()}
    //     }).done(function (message) {
    //         context.parent().parent().find('textarea').replaceWith('<p>' + $(this).parent().parent().find('textarea').text() + '</p>')
    //         console.log(message)
    //     }).fail(function (error) {
    //         console.log(error)
    //     })
    // });


    $(".edit").change( function() {
       console.log('Edit')
    });
// /user/removeFriend/
//     $(thisElement + '.btn-primary').on('click',function (e) {
//         e.preventDefault();
//         var id = thisElement.attr('userid');
//         $.ajax({
//             method: 'POST',
//             url: '/user/removeFriend/' + id,
//             data: {id: id}
//         }).done(function (message) {
//             thisElement.addClass('btn-outline-primary').removeClass('btn-primary').text('Удалено').attr('id','addFriend');
//         }).fail(function (error) {
//             console.log(error)
//         })
//     });
})

