<?php

namespace App\Http\Livewire;

use App\Models\Student;
use Livewire\Component;
use Livewire\WithPagination;

class Students extends Component
{
	

    public $ids;
	public $firstname;
	public $lastname;
	public $email;
	public $phone;
	public $searchTerm;

    //Reset Input Fields
	public function resetInputFields()
	{
		$this->firstname='';
		$this->lastname='';
		$this->email='';
		$this->phone='';
	}


	public function store()
	{
		$validatedData =$this->validate([
			'firstname' =>'required',
			'lastname' =>'required',
			'email' =>'required|email',
			'phone' =>'required|digits:11'

		]);

		Student::create($validatedData);
		session()->flash('message','Student Created Successfully!');
		$this->resetInputFields();
		$this->emit('studentAdded');
	}

    //Edit
	public function edit($id)
	{
       $student = Student::where('id',$id)->first();
       $this->ids = $student->id;
       $this->firstname = $student->firstname;
       $this->lastname = $student->lastname;
       $this->email = $student->email;
       $this->phone = $student->phone;
	}


    //Update
	public function update()
	{
		$this->validate([

         'firstname' =>'required',
		 'lastname' =>'required',
		 'email' =>'required|email',
		 'phone' =>'required|digits:11'
		]);
	
     if($this->ids)
     {
        $student=Student::find($this->ids);
  	    $student->update([
            
            'firstname' =>$this->firstname,
            'lastname' =>$this->lastname,
            'email' =>$this->email,
            'phone' =>$this->phone,

  	     ]);

        session()->flash('message','Student Updated Successfully!');
        $this->resetInputFields();
        $this->emit('studentUpdated');	 

     }
   }
  
   //Delete
   public function delete($id)
   {
  	  if($id)
  	  {
  	 	Student::where('id',$id)->delete();
  	 	session()->flash('message','Student Deleted Successfully!');
  	  }
   }

    use WithPagination;
    public function render()
    {   
    	$searchTerm='%'.$this->searchTerm .'%';

    	$students= Student::where('firstname','LIKE',$searchTerm)
                  ->orWhere('lastname','LIKE',$searchTerm)
                  ->orWhere('email','LIKE',$searchTerm)
                  ->orWhere('phone','LIKE',$searchTerm)
    	          ->orderBy('id','DESC')->paginate(5);

    	
        return view('livewire.students',['students'=>$students]);
    }

}
