# ONLINE-ORDERING-SYSTEM
  <strong>Online Ordering System</strong> for a restaurant.
 <br/>
 This web application has Laravel REST API with token authentication, Livewire for dynamic search functionality, Pusher js and Websockets for realtime notifications, and dynamic admin dashboard using Vue 2.
  <br/>
 <b>Author:</b> Renz De la Torre
 <br/> <br/>
 <strong>Technologies used:  </strong>
  <br/>
 HTML, Bootstrap, and Javascript for Customer Website Frontend,  <br/>
 Vue JS 2 for Admin Dashboard Frontend, <br/>
 Laravel 8 for Backend / API, <br/>
 MySQL for Database <br/>
 XAMPP for development server <br/>
  <br/>
 <strong>How to build and run locally:</strong> <br />
 <ol>
    <li>
      Clone repository using git clone https://github.com/Renz00/Online-Ordering-System.git
    </li>
     <li>
      cd into project directory.
    </li>
     <li>
      Run composer install
    </li>
     <li>
      Run npm install
    </li>
     <li>
      Copy .env.example file and rename it to .env then provide the necessary configurations.
    </li>
     <li>
      Run php artisan key:generate
    </li>
     <li>
      Create an empty MySQL database, keep in mind your configuration in the .env file.
    </li>
     <li>
      Run php artisan migrate
    </li>
     <li>
      Run php artisan serve
    </li>
 </ol>


<strong>Specific Objectives:</strong>
<ul>
    <li>
        Create an online Menu in the website.
       <ul>
           <li>Users can filter and search menu items.</li>
           <li>Customers can add menu items to their cart.</li>
           <li>Admin dashboard users can add, edit, and remove products</li>
       </ul> 
    </li>
    <li>
          Implement user account authentication.
       <ul>
           <li>Users can register to the system and obtain a user account.</li>
           <li>Login using email and password.</li>
           <li>Add and update user details and contact information.</li>
           <li>Users can only create an order if they are logged into an account.</li>
       </ul> 
    </li>
    <li>
          Allow customers to manage their orders.
       <ul>
           <li>Users can add, edit, and remove menu items from the orders.</li>
           <li>Customers can filter and search for specific orders. They can also see their order history.</li>
       </ul> 
    </li>
     <li>
          Reviews can be created for menu items.
       <ul>
           <li>Customers can add, edit, and remove their reviews.</li>
           <li>Admin dashboard users can manage and moderate the reviews.</li>
       </ul> 
    </li>
      <li>
          Reviews can be created for menu items.
       <ul>
           <li>Customers can add, edit, and remove their reviews.</li>
           <li>Admin dashboard users can manage and moderate the reviews.</li>
       </ul> 
    </li>
      <li>
          Admin dashboard users can receive notifications.
       <ul>
           <li>Notifications will be created when an order or review has been made.</li>
       </ul> 
    </li>
     <li>
          Admin dashboard will have monthly sales and best products reports.
    </li>
</ul>
 
 <em>*Check the DATABASE SCHEMA PDF file for the Database Structure</em>
 
 <strong>Website <em>Video Demo:</strong> [https://youtu.be/NaUB5dGDnWU](https://youtu.be/m8U_KADvrDA)</em>
 ![image](https://github.com/Renz00/Online-Ordering-System/assets/88235225/605a4267-0720-4655-a4fb-29e24a0c6285)
 ![image](https://github.com/Renz00/Online-Ordering-System/assets/88235225/248dc39a-d52f-4646-8002-486d0b748bcd)
![Capture2](https://user-images.githubusercontent.com/88235225/157015003-affbbcbc-f885-4bea-adfd-0df68210f4c4.PNG)
![Captured](https://user-images.githubusercontent.com/88235225/157015010-f56ce09f-ac89-42a6-b4ce-d4c1854f0a66.PNG)
![Captureddd](https://user-images.githubusercontent.com/88235225/157015021-4db6872b-a90d-4e91-af93-9b2b51c4db82.PNG)
![Capturesfsdfsd](https://user-images.githubusercontent.com/88235225/157015023-e474a407-8a7f-44cf-8026-45f9e4d47858.PNG)
  <br/>  <br/>
<strong>Admin Dashboard<em> Video Demo:<strong/>  [https://youtu.be/NaUB5dGDnWU](https://youtu.be/NaUB5dGDnWU)</em>
![awdasdasd](https://user-images.githubusercontent.com/88235225/157015030-50de7b1c-3c00-41ae-bee9-aa3513c1b32a.PNG)
![Capture](https://user-images.githubusercontent.com/88235225/157015035-12f66d1e-f804-4600-a6ec-c71c296e0768.PNG)
![sfsdfsd](https://user-images.githubusercontent.com/88235225/157015039-1a1a9d0f-5120-42db-b214-c1139dff8301.PNG)
![fdfsfsdfsd](https://user-images.githubusercontent.com/88235225/157015045-64c31bcf-88e4-46bb-8597-a10b6c8a0c8b.PNG)
