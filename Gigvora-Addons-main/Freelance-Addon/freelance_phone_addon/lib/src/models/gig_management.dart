import 'gig_timeline_item.dart';
import 'gig_faq.dart';
import 'gig_addon.dart';
import 'gig_package.dart';
import 'gig_requirement.dart';
import 'gig_change_request.dart';
import 'gig_review.dart';

class GigManagement {
  GigManagement({
    required this.title,
    required this.timeline,
    required this.faqs,
    required this.addons,
    required this.packages,
    required this.requirements,
    required this.changes,
    required this.reviews,
  });

  final String title;
  final List<GigTimelineItem> timeline;
  final List<GigFaq> faqs;
  final List<GigAddon> addons;
  final List<GigPackage> packages;
  final List<GigRequirement> requirements;
  final List<GigChangeRequest> changes;
  final List<GigReview> reviews;

  factory GigManagement.fromJson(Map<String, dynamic>? json) {
    final data = json ?? {};
    return GigManagement(
      title: data['gig']?['title']?.toString() ?? 'Gig',
      timeline: (data['timeline'] as List?)?.map((e) => GigTimelineItem.fromJson(e as Map<String, dynamic>?)).toList() ?? const [],
      faqs: (data['faqs'] as List?)?.map((e) => GigFaq.fromJson(e as Map<String, dynamic>?)).toList() ?? const [],
      addons: (data['addons'] as List?)?.map((e) => GigAddon.fromJson(e as Map<String, dynamic>?)).toList() ?? const [],
      packages: (data['packages'] as List?)?.map((e) => GigPackage.fromJson(e as Map<String, dynamic>?)).toList() ?? const [],
      requirements: (data['requirements'] as List?)?.map((e) => GigRequirement.fromJson(e as Map<String, dynamic>?)).toList() ?? const [],
      changes: (data['changes'] as List?)?.map((e) => GigChangeRequest.fromJson(e as Map<String, dynamic>?)).toList() ?? const [],
      reviews: (data['reviews'] as List?)?.map((e) => GigReview.fromJson(e as Map<String, dynamic>?)).toList() ?? const [],
    );
  }
}
