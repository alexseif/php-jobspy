import sys
import json
from jobspy import scrape_jobs

def main():
    try:
        input_data = sys.stdin.read()
        if not input_data.strip():
            print(json.dumps({"error": "No JSON input provided"}), file=sys.stderr)
            sys.exit(1)
            
        args = json.loads(input_data)
        
        site_name = args.get('site_name', ['indeed'])
        search_term = args.get('search_term', '')
        location = args.get('location', '')
        results_wanted = args.get('results_wanted', 10)
        
        # Ensure site_name is a list of strings
        if isinstance(site_name, str):
            site_name = [site_name]
            
        jobs = scrape_jobs(
            site_name=site_name,
            search_term=search_term,
            location=location,
            results_wanted=int(results_wanted)
        )
        
        print(jobs.to_json(orient="records", date_format="iso"))
        
    except Exception as e:
        print(json.dumps({"error": str(e)}), file=sys.stderr)
        sys.exit(1)

if __name__ == '__main__':
    main()
