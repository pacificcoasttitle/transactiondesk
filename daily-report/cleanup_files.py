#!/usr/bin/env python3
"""
Clean up daily-report folder - Remove unused files and keep only essentials
"""

import os
import shutil

def cleanup_daily_report():
    """Clean up the daily-report folder"""
    
    print("🧹 CLEANING UP DAILY-REPORT FOLDER")
    print("=" * 60)
    
    # Files to KEEP (essential files only)
    essential_files = {
        # Final Reports (only the best versions)
        'Branch-Analytics-Final-Improved.html',
        'R-14-Mapped-Report.html', 
        'Title-Officer-Clean-Report.html',
        
        # Demo Templates (for reference)
        'Branch-Analytics-Demo.html',
        'R-14-Demo.html',
        'Title-Officer-Report-Demo.html',
        
        # Data Files
        'Openings.xlsx',
        'ClosedOrders.xlsx', 
        'Revenue.xlsx',
        'SalesRepMapping.xlsx',
        'TitleOfficerMapping.xlsx',
        
        # Core System Files
        'build_report.py',
        'transforms.py',
        'sections.yml',
        'requirements.txt',
        'env.example',
        
        # Documentation
        'Daily-Report-README.md',
        'ARCHITECT.md',
        'REPORT-LOGIC-DOCUMENTATION.md',
        
        # Final Scripts (only the working ones)
        'create_title_officer_mapping_only.py',  # The clean title officer script
        
        # Templates folder
        'templates',
        'SampleExcelFile'
    }
    
    # Get all files in the directory
    all_files = []
    for item in os.listdir('.'):
        if os.path.isfile(item):
            all_files.append(item)
        elif os.path.isdir(item) and item in essential_files:
            all_files.append(item)  # Keep essential directories
    
    # Files to DELETE
    files_to_delete = []
    for file in all_files:
        if file not in essential_files:
            files_to_delete.append(file)
    
    print(f"📊 CLEANUP ANALYSIS:")
    print(f"   Total files found: {len(all_files)}")
    print(f"   Essential files to keep: {len(essential_files)}")
    print(f"   Files to delete: {len(files_to_delete)}")
    
    print(f"\n✅ KEEPING THESE ESSENTIAL FILES:")
    for file in sorted(essential_files):
        if os.path.exists(file):
            print(f"   ✅ {file}")
        else:
            print(f"   ⚠️  {file} (not found)")
    
    print(f"\n🗑️  DELETING THESE UNUSED FILES:")
    deleted_count = 0
    for file in sorted(files_to_delete):
        try:
            if os.path.isfile(file):
                os.remove(file)
                print(f"   🗑️  {file}")
                deleted_count += 1
            elif os.path.isdir(file):
                shutil.rmtree(file)
                print(f"   🗑️  {file}/ (directory)")
                deleted_count += 1
        except Exception as e:
            print(f"   ❌ Failed to delete {file}: {e}")
    
    print(f"\n🎉 CLEANUP COMPLETE!")
    print(f"   ✅ Deleted {deleted_count} unused files")
    print(f"   ✅ Kept {len([f for f in essential_files if os.path.exists(f)])} essential files")
    
    # Show final clean structure
    print(f"\n📁 FINAL CLEAN STRUCTURE:")
    remaining_files = []
    for item in os.listdir('.'):
        remaining_files.append(item)
    
    for file in sorted(remaining_files):
        if os.path.isdir(file):
            print(f"   📁 {file}/")
        else:
            print(f"   📄 {file}")

if __name__ == "__main__":
    cleanup_daily_report()
