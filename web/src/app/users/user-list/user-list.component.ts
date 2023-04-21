import { Component, OnInit, ViewChild } from '@angular/core';
import { first } from 'rxjs';
import { UsersService } from 'src/app/services/users.service';
import { MatTable } from '@angular/material/table';
import { Router } from '@angular/router';

//interface or model for user list object
export interface UserElement {
  id: string;
  first_name: string;
  last_name: string;
  email: any;
  phone: any;
  designation: any;
  zip_code: any;
  date_of_birth: any;
  experience: any;
  subscribe: any;
  comments: any;
}

@Component({
  selector: 'app-user-list',
  templateUrl: './user-list.component.html',
  styleUrls: ['./user-list.component.scss'],
})
export class UserListComponent implements OnInit {
  commonError: string = '';
  displayedColumns: string[] = [
    'id',
    'first_name',
    'last_name',
    'email',
    'phone',
    'designation',
    'date_of_birth',
    'experience',
    'comments',
  ];
  dataSource: any = []; // data source for table
  loader: boolean = true; // loader value 
  @ViewChild(MatTable) table: any | MatTable<UserElement>;

  constructor(private usersService: UsersService, private router: Router) {}

  ngOnInit(): void {
    // load data from api
    this.getUsersListData();
  }

  // Get User Data from IFormBuilder
  getUsersListData() {
    this.usersService
      .getUsersListData()
      .pipe(first())
      .subscribe(
        (data: any) => {
          this.loader = false;
          this.dataSource = [];
          //check response status
          if (data.status == false) {
            this.commonError = data.body;
          } else {
            //check response length
            if(data.body.data && data.body.data.length>0){
              this.dataSource = data.body.data;
            }
            this.commonError = '';
          }
        }        
      );
  }

  // Navigate to create user page
  addUser() {
    this.router.navigate(['/user-create'], { queryParams: { type: 'create' } });
  }
}
