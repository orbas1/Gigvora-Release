import 'package:equatable/equatable.dart';

class KeywordPrice extends Equatable {
  const KeywordPrice({
    required this.keyword,
    required this.cpc,
    required this.cpa,
    required this.cpm,
  });

  factory KeywordPrice.fromJson(Map<String, dynamic> json) {
    return KeywordPrice(
      keyword: json['keyword'] as String,
      cpc: (json['cpc'] as num).toDouble(),
      cpa: (json['cpa'] as num).toDouble(),
      cpm: (json['cpm'] as num).toDouble(),
    );
  }

  final String keyword;
  final double cpc;
  final double cpa;
  final double cpm;

  Map<String, dynamic> toJson() => {
        'keyword': keyword,
        'cpc': cpc,
        'cpa': cpa,
        'cpm': cpm,
      };

  @override
  List<Object?> get props => [keyword, cpc, cpa, cpm];
}
