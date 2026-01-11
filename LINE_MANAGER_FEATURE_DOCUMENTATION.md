# Line Manager Feature Documentation

## Overview
This document describes the line manager feature in the HR Management System, specifically for onboarding and exit clearance requests. The feature allows HR to enter line manager information as text fields (name and email) instead of selecting from a dropdown, and provides the ability to edit line manager information later.

## Table of Contents
1. [Feature Description](#feature-description)
2. [Onboarding Request Workflow](#onboarding-request-workflow)
3. [Exit Clearance Request Workflow](#exit-clearance-request-workflow)
4. [Editing Line Manager Information](#editing-line-manager-information)
5. [Database Schema](#database-schema)
6. [Technical Implementation](#technical-implementation)

---

## Feature Description

### Purpose
The line manager feature allows HR administrators to:
- **Enter line manager information manually** as text fields (name and email) instead of selecting from a predefined dropdown list
- **Edit line manager information** at any time, as line managers can change during an employee's tenure
- **Track line manager approvals** for exit clearance requests

### Key Benefits
1. **Flexibility**: Line managers don't need to be system users to be referenced
2. **Accuracy**: HR can enter the correct line manager even if they're not in the system
3. **Maintainability**: Line manager information can be updated as organizational changes occur
4. **Simplicity**: No need to maintain a separate list of line managers in the system

---

## Onboarding Request Workflow

### Step 1: Create Onboarding Request

When creating a new onboarding request, HR can:

1. Navigate to **Onboarding Requests** → **Create New Request**
2. Fill in employee information
3. **Enter Line Manager Details** (Optional):
   - **Line Manager Name**: Full name of the employee's line manager
   - **Line Manager Email**: Email address of the line manager

**Form Fields:**
```
Line Manager Name (Optional)
┌─────────────────────────────────────────────┐
│ Enter line manager's full name              │
└─────────────────────────────────────────────┘
Enter the full name of the employee's line manager

Line Manager Email (Optional)
┌─────────────────────────────────────────────┐
│ manager@company.com                         │
└─────────────────────────────────────────────┘
Enter the line manager's email address
```

**Important Notes:**
- Both fields are optional for onboarding requests
- Line manager information can be added or modified later via the edit function
- The line manager will not receive automated notifications for onboarding (unlike exit clearance)

### Step 2: View Onboarding Request

After creating the request:
1. The request details page shows all information including line manager
2. Line manager name and email are displayed in the "Request Details" section
3. An **Edit Request** button is available (if user has permission)

**Display Format:**
```
Line Manager
John Smith
john.smith@company.com
```

### Step 3: Edit Onboarding Request (if needed)

If line manager information needs to be updated:
1. Click **Edit Request** button on the request details page
2. Update the line manager name and/or email fields
3. Click **Update Request** to save changes

---

## Exit Clearance Request Workflow

### Step 1: Create Exit Clearance Request

When creating an exit clearance request:

1. Navigate to **Exit Clearance Requests** → **Create New Request**
2. Select the employee who is exiting
3. Enter the exit date and reason
4. **Enter Line Manager Details** (Required):
   - **Line Manager Name**: Full name of the employee's line manager (required)
   - **Line Manager Email**: Email address of the line manager (required)

**Form Fields:**
```
Line Manager Name
┌─────────────────────────────────────────────┐
│ Enter line manager's full name              │
└─────────────────────────────────────────────┘
Enter the full name of the employee's line manager

Line Manager Email
┌─────────────────────────────────────────────┐
│ manager@company.com                         │
└─────────────────────────────────────────────┘
Line manager will receive an email notification to approve this exit clearance request
```

**Important Notes:**
- Both fields are **required** for exit clearance requests
- An approval email is automatically sent to the line manager email address
- The line manager must approve before HR can assign clearance tasks

### Step 2: Line Manager Approval

After the exit clearance request is created:

1. **Approval Email Sent**: The line manager receives an email with:
   - Employee details
   - Exit date and reason
   - Approval link

2. **Line Manager Actions**: The line manager can:
   - **Approve** the exit clearance request
   - **Reject** the exit clearance request with notes
   
3. **Approval Status Display**:
   ```
   Line Manager Approval
   
   Line Manager: John Smith
                 john.smith@company.com
   
   Approval Status: [Pending/Approved/Rejected]
   ```

### Step 3: After Approval

Once the line manager approves:
- HR can assign clearance tasks to various departments
- The approval status is permanently recorded
- The clearance process continues

If the line manager rejects:
- HR is notified
- The request remains in rejected status
- HR can edit the request or create a new one

### Step 4: Edit Exit Clearance Request (if needed)

HR can update exit clearance information including line manager details:

1. Click **Edit Request** button on the request details page
2. Update the following fields as needed:
   - Line Manager Name
   - Line Manager Email
   - Exit Date
   - Reason
   - Status
3. Click **Update Request** to save changes

**Important:** Changing the line manager email after approval may be necessary if:
- The original line manager information was incorrect
- The line manager has changed due to organizational restructuring
- Contact information needs to be updated

---

## Editing Line Manager Information

### When to Edit

Line manager information should be edited when:
- **Incorrect Information**: Initial data entry was incorrect
- **Manager Change**: The employee's line manager has changed
- **Contact Update**: Line manager's email address has changed
- **Organizational Restructure**: Department changes affect line manager assignments

### How to Edit

#### For Onboarding Requests:
1. Navigate to the onboarding request details page
2. Click the **Edit Request** button (requires 'edit onboarding' permission)
3. Update line manager name and/or email fields
4. Update other fields if needed (expected completion date, status, notes)
5. Click **Update Request**

#### For Exit Clearance Requests:
1. Navigate to the exit clearance request details page
2. Click the **Edit Request** button (requires 'edit exit clearance' permission)
3. Update line manager name and/or email fields
4. Update other fields if needed (exit date, reason, status)
5. Click **Update Request**

### Important Considerations

- **Exit Clearance Approval**: If you change the line manager email AFTER approval has been sent, the new email will not automatically receive an approval request. The approval status remains with the original email.
- **Historical Record**: Previous line manager information is not retained in history. The current display always shows the latest information.
- **Permissions**: Only users with appropriate permissions can edit requests.

---

## Database Schema

### Tables Modified

#### onboarding_requests Table
```sql
-- New field added
line_manager_name VARCHAR(255) NULL  -- Full name of line manager

-- Existing fields (for reference)
line_manager_id BIGINT UNSIGNED NULL  -- Foreign key to users (optional, for backward compatibility)
line_manager_email VARCHAR(255) NULL  -- Email of line manager
```

#### exit_clearance_requests Table
```sql
-- New field added
line_manager_name VARCHAR(255) NULL  -- Full name of line manager

-- Existing fields (for reference)
line_manager_id BIGINT UNSIGNED NULL  -- Foreign key to users (optional, for backward compatibility)
line_manager_email VARCHAR(255) NULL  -- Email of line manager
line_manager_approval_status ENUM('pending', 'approved', 'rejected')
line_manager_approved_at TIMESTAMP NULL
line_manager_approval_notes TEXT NULL
```

### Migration File
The migration `2026_01_11_150300_add_line_manager_name_to_requests.php` adds the `line_manager_name` field to both tables.

---

## Technical Implementation

### Backend Changes

#### Controllers Updated

**OnboardingRequestController.php**
- `store()`: Added validation for `line_manager_name`
- `update()`: Added validation for `line_manager_name` and `line_manager_email`
- Removed validation that required line manager email to match selected user

**ExitClearanceRequestController.php**
- `store()`: Added validation for `line_manager_name`, made `line_manager_id` optional
- `update()`: Added validation for `line_manager_name` and `line_manager_email`
- Removed validation that required line manager email to match selected user

#### Models Updated

**OnboardingRequest.php**
- Added `line_manager_name` to `$fillable` array

**ExitClearanceRequest.php**
- Added `line_manager_name` to `$fillable` array

### Frontend Changes

#### Views Updated

**Onboarding Request Views:**
- `create.blade.php`: Changed from dropdown to text input fields
- `edit.blade.php`: Added line manager name and email fields
- `show.blade.php`: Updated display to show `line_manager_name` with fallback to related user

**Exit Clearance Request Views:**
- `create.blade.php`: Changed from dropdown to text input fields
- `edit.blade.php`: Added line manager name and email fields
- `show.blade.php`: Updated display to show `line_manager_name` with fallback to related user, added edit button

#### JavaScript Changes
- Removed `updateLineManagerEmail()` function that auto-filled email from dropdown selection
- Forms now use plain text inputs without auto-population

### Validation Rules

**Onboarding Requests:**
```php
'line_manager_name' => 'nullable|string|max:255'
'line_manager_email' => 'nullable|email'
```

**Exit Clearance Requests:**
```php
'line_manager_name' => 'required|string|max:255'
'line_manager_email' => 'required|email'
```

---

## Usage Examples

### Example 1: Creating an Onboarding Request with Line Manager

```
1. Go to Onboarding Requests > Create New Request
2. Select Employee: John Doe (EMP001)
3. Enter Personal Email: john.doe@personal.com
4. Enter Line Manager Name: Sarah Johnson
5. Enter Line Manager Email: sarah.johnson@company.com
6. Set Expected Completion Date: 2026-02-01
7. Select departments and tasks
8. Click "Create Request"

Result: Request created with line manager information stored as text
```

### Example 2: Creating an Exit Clearance Request

```
1. Go to Exit Clearance Requests > Create New Request
2. Select Employee: Jane Smith (EMP002)
3. Enter Exit Date: 2026-01-31
4. Enter Reason: Relocation to another city
5. Enter Line Manager Name: Michael Brown
6. Enter Line Manager Email: michael.brown@company.com
7. Click "Create Request"

Result: 
- Exit request created
- Approval email sent to michael.brown@company.com
- Request status: Pending (awaiting line manager approval)
```

### Example 3: Editing Line Manager After Organizational Change

```
Scenario: Employee's line manager changed from Sarah Johnson to Robert Davis

1. Navigate to the Onboarding Request details page
2. Click "Edit Request"
3. Update Line Manager Name: Robert Davis
4. Update Line Manager Email: robert.davis@company.com
5. Click "Update Request"

Result: Line manager information updated to reflect current organizational structure
```

---

## Best Practices

1. **Accuracy**: Always verify line manager information before creating requests
2. **Updates**: Keep line manager information current as organizational changes occur
3. **Communication**: Inform line managers when they are referenced in exit clearance requests
4. **Validation**: Double-check email addresses to ensure approval emails reach the intended recipient
5. **Documentation**: Document the reason for line manager changes in request notes when editing

---

## Troubleshooting

### Issue: Line manager not receiving approval email
**Solution:** 
- Verify the email address is correct in the exit clearance request
- Check your email server logs
- Edit the request to update the email if needed

### Issue: Cannot edit line manager information
**Solution:**
- Verify you have the appropriate permission ('edit onboarding' or 'edit exit clearance')
- Contact your system administrator for permission assignment

### Issue: Old line manager still showing
**Solution:**
- The system displays the most recent information
- If you see old information, refresh the page after saving
- Check if you properly saved the changes after editing

---

## Future Enhancements

Potential improvements to consider:
1. **History Tracking**: Maintain a log of line manager changes
2. **Auto-suggestion**: Auto-complete based on previously entered line manager names
3. **Bulk Update**: Update line manager across multiple employees
4. **Validation**: Check email domain against company email patterns
5. **Notifications**: Notify old line manager when their information is updated

---

## Support

For technical support or questions about this feature:
- Review this documentation
- Check system logs for error messages
- Contact your system administrator
- Refer to the main README.md for general system information

---

**Last Updated:** January 11, 2026  
**Version:** 1.0  
**Author:** HR Management System Development Team
