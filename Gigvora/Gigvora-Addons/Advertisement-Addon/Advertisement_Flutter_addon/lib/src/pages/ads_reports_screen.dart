import 'package:flutter/material.dart';

class AdsReportsScreen extends StatelessWidget {
  const AdsReportsScreen({super.key});

  static const routeName = '/ads/reports';

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Ads Reports')),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Expanded(
                  child: TextField(
                    decoration: const InputDecoration(labelText: 'Date Range'),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: DropdownButtonFormField<String>(
                    decoration: const InputDecoration(labelText: 'Campaign'),
                    items: const [
                      DropdownMenuItem(value: 'all', child: Text('All Campaigns')),
                    ],
                    onChanged: (_) {},
                  ),
                ),
              ],
            ),
            const SizedBox(height: 16),
            Expanded(
              child: ListView(
                children: [
                  Card(
                    child: Padding(
                      padding: const EdgeInsets.all(12),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: const [
                          Text('Performance Overview', style: TextStyle(fontWeight: FontWeight.bold)),
                          SizedBox(height: 8),
                          Text('Charts would render here using host chart library.'),
                        ],
                      ),
                    ),
                  ),
                  Card(
                    child: DataTable(columns: const [
                      DataColumn(label: Text('Campaign')),
                      DataColumn(label: Text('Impressions')),
                      DataColumn(label: Text('Clicks')),
                      DataColumn(label: Text('CTR')),
                      DataColumn(label: Text('Spend')),
                    ], rows: const [
                      DataRow(cells: [
                        DataCell(Text('Campaign A')),
                        DataCell(Text('120k')),
                        DataCell(Text('4.2k')),
                        DataCell(Text('3.5%')),
                        DataCell(Text('1200')),
                      ]),
                    ]),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}
