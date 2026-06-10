<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\Shipment;
use App\Models\ShipmentEvent;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'branch_user']);
        Role::create(['name' => 'customer']);

        // Branches - Malaysia
        $kl = Branch::create(['name'=>'Kuala Lumpur - Main','name_ar'=>'كوالالمبور - الرئيسي','code'=>'KUL','city'=>'Kuala Lumpur','city_ar'=>'كوالالمبور','country'=>'Malaysia','country_ar'=>'ماليزيا','phone'=>'+601199999001','email'=>'kl@7expres.com']);
        $pg = Branch::create(['name'=>'Penang','name_ar'=>'بينانغ','code'=>'PEN','city'=>'Penang','city_ar'=>'بينانغ','country'=>'Malaysia','country_ar'=>'ماليزيا']);
        $jb = Branch::create(['name'=>'Johor Bahru','name_ar'=>'جوهور باهرو','code'=>'JHB','city'=>'Johor Bahru','city_ar'=>'جوهور باهرو','country'=>'Malaysia','country_ar'=>'ماليزيا']);

        // Branches - Yemen
        $sanaa    = Branch::create(['name'=>"Sana'a - Main",'name_ar'=>'صنعاء - الرئيسي','code'=>'SAH','city'=>"Sana'a",'city_ar'=>'صنعاء','country'=>'Yemen','country_ar'=>'اليمن','phone'=>'+9671999001','email'=>'sanaa@7expres.com']);
        $aden     = Branch::create(['name'=>'Aden','name_ar'=>'عدن','code'=>'ADE','city'=>'Aden','city_ar'=>'عدن','country'=>'Yemen','country_ar'=>'اليمن']);
        $taiz     = Branch::create(['name'=>'Taiz','name_ar'=>'تعز','code'=>'TAI','city'=>'Taiz','city_ar'=>'تعز','country'=>'Yemen','country_ar'=>'اليمن']);
        $hodeidah = Branch::create(['name'=>'Hodeidah','name_ar'=>'الحديدة','code'=>'HOD','city'=>'Hodeidah','city_ar'=>'الحديدة','country'=>'Yemen','country_ar'=>'اليمن']);

        // Admin user
        $adminUser = User::create(['name'=>'مدير النظام','email'=>'admin@7expres.com','password'=>Hash::make('password'),'branch_id'=>$kl->id,'locale'=>'ar','is_active'=>true]);
        $adminUser->assignRole('admin');

        // Branch users
        $klUser = User::create(['name'=>'KL Branch Manager','email'=>'kl.user@7expres.com','password'=>Hash::make('password'),'branch_id'=>$kl->id,'locale'=>'ar','is_active'=>true]);
        $klUser->assignRole('branch_user');

        $sanaaUser = User::create(['name'=>'موظف فرع صنعاء','email'=>'sanaa.user@7expres.com','password'=>Hash::make('password'),'branch_id'=>$sanaa->id,'locale'=>'ar','is_active'=>true]);
        $sanaaUser->assignRole('branch_user');

        // Drivers
        $driver1 = Driver::create(['name'=>'Ahmed Al-Yemeni','phone'=>'+967771234567','license_number'=>'YE-12345','vehicle_number'=>'YE-A-1234','branch_id'=>$sanaa->id,'is_active'=>true]);
        $driver2 = Driver::create(['name'=>'Mohammed Azmi','phone'=>'+60121234567','license_number'=>'MY-56789','vehicle_number'=>'WA1234B','branch_id'=>$kl->id,'is_active'=>true]);

        // Sample customers
        $sender1 = Customer::create(['full_name'=>'Ali Hassan Mohammed','phone'=>'+60121234001','email'=>'ali@example.com','address'=>'Jalan Ampang 12','city'=>'Kuala Lumpur','country'=>'Malaysia','type'=>'sender']);
        $recv1   = Customer::create(['full_name'=>'محمد الحضرمي','phone'=>'+967771234001','address'=>'شارع النصر، حي المدينة','city'=>"Sana'a",'country'=>'Yemen','type'=>'receiver']);

        // Sample shipment 1 - in transit
        $s1 = Shipment::create([
            'tracking_number'=>'7XPKUL001SAH','shipment_date'=>now()->subDays(3)->toDateString(),'shipment_type'=>'standard','status'=>'in_transit','delivery_type'=>'door_to_door',
            'origin_branch_id'=>$kl->id,'destination_branch_id'=>$sanaa->id,'current_branch_id'=>$kl->id,'driver_id'=>$driver2->id,
            'sender_id'=>$sender1->id,'sender_name'=>$sender1->full_name,'sender_phone'=>$sender1->phone,'sender_city'=>$sender1->city,'sender_country'=>$sender1->country,'sender_address'=>$sender1->address,
            'receiver_id'=>$recv1->id,'receiver_name'=>$recv1->full_name,'receiver_phone'=>$recv1->phone,'receiver_city'=>$recv1->city,'receiver_country'=>$recv1->country,'receiver_address'=>$recv1->address,
            'notes'=>'Handle with care','created_by'=>$adminUser->id,'updated_by'=>$adminUser->id,
        ]);
        $s1->packages()->create(['package_number'=>1,'description'=>'Electronics - Laptop','length'=>40,'width'=>30,'height'=>10,'actual_weight'=>2.5,'volumetric_weight'=>2.4,'chargeable_weight'=>2.5]);
        $s1->updateTotals();
        ShipmentEvent::create(['shipment_id'=>$s1->id,'status'=>'created','branch_id'=>$kl->id,'user_id'=>$adminUser->id,'event_at'=>now()->subDays(3)]);
        ShipmentEvent::create(['shipment_id'=>$s1->id,'status'=>'collected','branch_id'=>$kl->id,'user_id'=>$adminUser->id,'event_at'=>now()->subDays(2),'notes'=>'Collected from sender']);
        ShipmentEvent::create(['shipment_id'=>$s1->id,'status'=>'in_transit','branch_id'=>$kl->id,'user_id'=>$adminUser->id,'event_at'=>now()->subDay(),'notes'=>'Departed KUL airport']);

        // Sample shipment 2 - delivered
        $s2 = Shipment::create([
            'tracking_number'=>'7XPKUL002SAH','shipment_date'=>now()->subDays(7)->toDateString(),'shipment_type'=>'express','status'=>'delivered','delivery_type'=>'door_to_door',
            'origin_branch_id'=>$kl->id,'destination_branch_id'=>$sanaa->id,'current_branch_id'=>$sanaa->id,'driver_id'=>$driver1->id,
            'sender_name'=>'Sarah Abdullah','sender_phone'=>'+60123456789','sender_city'=>'Penang','sender_country'=>'Malaysia',
            'receiver_name'=>'خالد الشرابي','receiver_phone'=>'+967712345678','receiver_city'=>"Sana'a",'receiver_country'=>'Yemen','receiver_address'=>'شارع الزبيري',
            'created_by'=>$adminUser->id,'updated_by'=>$adminUser->id,
        ]);
        $s2->packages()->create(['package_number'=>1,'description'=>'Clothing','length'=>50,'width'=>40,'height'=>20,'actual_weight'=>3.0,'volumetric_weight'=>8.0,'chargeable_weight'=>8.0]);
        $s2->packages()->create(['package_number'=>2,'description'=>'Books','length'=>30,'width'=>25,'height'=>15,'actual_weight'=>4.0,'volumetric_weight'=>2.25,'chargeable_weight'=>4.0]);
        $s2->updateTotals();
        foreach(['created','collected','in_transit','arrived_at_branch','out_for_delivery','delivered'] as $i => $st) {
            ShipmentEvent::create(['shipment_id'=>$s2->id,'status'=>$st,'branch_id'=>in_array($st,['arrived_at_branch','out_for_delivery','delivered'])?$sanaa->id:$kl->id,'user_id'=>$adminUser->id,'event_at'=>now()->subDays(6-$i)]);
        }
    }
}
