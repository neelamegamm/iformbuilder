import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { first } from 'rxjs';
import { UsersService } from 'src/app/services/users.service';

@Component({
  selector: 'app-user-create',
  templateUrl: './user-create.component.html',
  styleUrls: ['./user-create.component.scss'],
})
export class UserCreateComponent implements OnInit {
  public createUserForm: any = FormGroup; // Create form variable
  commonError: any; // used for common error display
  number: any; // used for common error display
  loader: any | boolean = false; // used for loader
  constructor(
    private usersService: UsersService,
    private router: Router,
    private formBuilder: FormBuilder
  ) {}

  ngOnInit(): void {
    // Create form initialize and assign validation
    this.createUserForm = this.formBuilder.group({
      first_name: ['', Validators.required],
      last_name: ['', Validators.required],
      email: ['', [Validators.required, Validators.email]],
      phone: [
        '',
        [
          Validators.required,
          Validators.pattern('^[0-9]*$'),
          Validators.minLength(10),
          Validators.maxLength(10),
        ],
      ],
      designation: ['', Validators.required],
      zip_code: [
        '',
        [
          Validators.required,
          Validators.pattern('^[0-9]*$'),
          Validators.minLength(5),
          Validators.maxLength(5),
        ],
      ],
      date_of_birth: ['', Validators.required],
      experience: [''],
      subscribe: ['1'],
      comments: [''],
    });
  }

  // Create form submit function
  submitForm() {
    // check validation
    if (this.createUserForm.valid) {
      this.createUserForm.controls['phone'].setValue(this.number);
      this.loader = true;
      this.usersService
        .createUser(this.createUserForm.value)
        .pipe(first())
        .subscribe(
          (data: any) => {
            this.loader = false;
            if (data.status == false) {
              this.commonError = data.body;
            } else {
               // after craate record navigate to user list page
              this.router.navigate(['/users']);
            }
          }          
        );
    }else{
      // for validation
      this.createUserForm.markAllAsTouched();
    }
  }
  
  // Reset function
  resetForm() {
    this.createUserForm.reset();
  }

  // Cancel function
  cancelForm() {
    this.router.navigate(['/users']);
  }

}
