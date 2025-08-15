#!/usr/bin/env python3
"""
Sample test script to generate a demo report with sample data
This shows what the Excel-style template will look like
"""

import yaml
from datetime import datetime
from jinja2 import Environment, FileSystemLoader

# Sample data that mimics your database structure
sample_data = {
    'meta': {
        'title': 'Pacific Coast Title - Daily Executive Report',
        'show_day_banner': True
    },
    'day_banner': 'Daily Report for Friday, August 15, 2025',
    'generated_at': datetime.now().strftime('%Y-%m-%d %H:%M:%S'),
    'sections': [
        {
            'name': 'Executive Summary',
            'subtables': [
                {
                    'title': 'Key Performance Indicators',
                    'columns': ['Metric', 'Value'],
                    'rows': [
                        {'Metric': 'Total Openings Today', 'Value': '12'},
                        {'Metric': 'Total Closings Today', 'Value': '8'},
                        {'Metric': 'Total Revenue Today', 'Value': '$3,245,000'},
                        {'Metric': 'Average Transaction Value', 'Value': '$405,625'}
                    ]
                }
            ]
        },
        {
            'name': 'Title Division',
            'subtables': [
                {
                    'title': 'New Openings Today',
                    'columns': ['File Number', 'Property Address', 'Customer', 'Sales Price', 'Type', 'Sales Rep', 'Branch'],
                    'rows': [
                        {
                            'File Number': 'T-240815-001',
                            'Property Address': '123 Oak Street, Los Angeles, CA 90210',
                            'Customer': 'John Smith',
                            'Sales Price': '$750,000',
                            'Type': 'Purchase',
                            'Sales Rep': 'Sarah Johnson',
                            'Branch': 'Main Office - LA'
                        },
                        {
                            'File Number': 'T-240815-002',
                            'Property Address': '456 Pine Avenue, Orange County, CA 92602',
                            'Customer': 'Maria Garcia',
                            'Sales Price': '$925,000',
                            'Type': 'Refinance',
                            'Sales Rep': 'Mike Chen',
                            'Branch': 'Orange County'
                        },
                        {
                            'File Number': 'T-240815-003',
                            'Property Address': '789 Elm Drive, San Diego, CA 92101',
                            'Customer': 'Robert Wilson',
                            'Sales Price': '$1,150,000',
                            'Type': 'Purchase',
                            'Sales Rep': 'Jennifer Lee',
                            'Branch': 'San Diego'
                        }
                    ]
                },
                {
                    'title': 'Closings Completed Today',
                    'columns': ['File Number', 'Property Address', 'Customer', 'Sales Price', 'Type', 'Sales Rep', 'Branch'],
                    'rows': [
                        {
                            'File Number': 'T-240801-045',
                            'Property Address': '321 Maple Court, Los Angeles, CA 90028',
                            'Customer': 'Lisa Anderson',
                            'Sales Price': '$680,000',
                            'Type': 'Purchase',
                            'Sales Rep': 'David Brown',
                            'Branch': 'Main Office - LA'
                        },
                        {
                            'File Number': 'T-240805-023',
                            'Property Address': '654 Cedar Boulevard, Riverside, CA 92501',
                            'Customer': 'James Thompson',
                            'Sales Price': '$525,000',
                            'Type': 'Refinance',
                            'Sales Rep': 'Amy Rodriguez',
                            'Branch': 'Riverside'
                        }
                    ]
                }
            ]
        },
        {
            'name': 'Escrow Division',
            'subtables': [
                {
                    'title': 'New Openings Today',
                    'columns': ['File Number', 'Property Address', 'Customer', 'Sales Price', 'Type', 'Sales Rep', 'Branch'],
                    'rows': [
                        {
                            'File Number': 'E-240815-001',
                            'Property Address': '987 Birch Lane, Ventura, CA 93001',
                            'Customer': 'Patricia Davis',
                            'Sales Price': '$465,000',
                            'Type': 'Purchase',
                            'Sales Rep': 'Carlos Martinez',
                            'Branch': 'Ventura'
                        }
                    ]
                },
                {
                    'title': 'Closings Completed Today',
                    'columns': ['File Number', 'Property Address', 'Customer', 'Sales Price', 'Type', 'Sales Rep', 'Branch'],
                    'rows': [
                        {
                            'File Number': 'E-240810-017',
                            'Property Address': '147 Spruce Street, Orange County, CA 92614',
                            'Customer': 'Michael Johnson',
                            'Sales Price': '$890,000',
                            'Type': 'Purchase',
                            'Sales Rep': 'Nicole Kim',
                            'Branch': 'Orange County'
                        }
                    ]
                }
            ]
        },
        {
            'name': 'Branch Performance',
            'subtables': [
                {
                    'title': 'Revenue by Branch',
                    'columns': ['Branch', 'Division', 'Closings Today', 'Revenue Today', 'Avg Transaction'],
                    'rows': [
                        {
                            'Branch': 'Main Office - LA',
                            'Division': 'Title',
                            'Closings Today': '3',
                            'Revenue Today': '$1,205,000',
                            'Avg Transaction': '$401,667'
                        },
                        {
                            'Branch': 'Orange County',
                            'Division': 'Escrow',
                            'Closings Today': '2',
                            'Revenue Today': '$890,000',
                            'Avg Transaction': '$445,000'
                        },
                        {
                            'Branch': 'San Diego',
                            'Division': 'Title',
                            'Closings Today': '1',
                            'Revenue Today': '$1,150,000',
                            'Avg Transaction': '$1,150,000'
                        }
                    ]
                }
            ]
        }
    ]
}

def generate_sample_report():
    """Generate a sample report to show the Excel-style formatting"""
    env = Environment(loader=FileSystemLoader('templates'))
    template = env.get_template('report.html')
    
    html_content = template.render(
        meta=sample_data['meta'],
        day_banner=sample_data['day_banner'],
        generated_at=sample_data['generated_at'],
        sections=sample_data['sections']
    )
    
    with open('SampleExcelStyleReport.html', 'w', encoding='utf-8') as f:
        f.write(html_content)
    
    print("✅ Sample Excel-style report generated: SampleExcelStyleReport.html")
    print("📝 Open this file in your browser to see the Excel-like formatting")
    print("💡 This shows exactly how your daily reports will look to executives")

if __name__ == "__main__":
    generate_sample_report()
